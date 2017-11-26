<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia;

/**
 * Handles parsing messages and running commands from them.
 *
 * @property \CharlotteDunois\Livia\LiviaClient        $client      The client which initiated the instance.
 * @property array                                     $inhibitors  Functions that can block commands from running.
 */
class CommandDispatcher {
    protected $client;
    
    protected $inhibitors = array();
    
    /**
     * @internal
     */
    public $awaiting = array();
    
    protected $commandPatterns = array();
    protected $results;
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        $this->client = $client;
        
        $this->results = new \CharlotteDunois\Yasmin\Utils\Collection();
    }
    
    /**
     * @internal
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception('Unknown property \CharlotteDunois\Livia\CommandDispatcher::'.$name);
    }
    
    /**
     * Adds an inhibitor. The inhibitor is supposed to return false, if the command should not be blocked. Otherwise it should return a string (as reason) or an array, containing as first element the reason and as second element a Promise (which resolves to a Message), a Message instance or null. The inhibitor can return a Promise (for async computation).
     * @param callable  $inhibitor
     * @return $this
     */
    function addInhibitor(callable $inhibitor) {
        if(!\in_array($inhibitor, $this->inhibitors)) {
            $this->inhibitors[] = $inhibitor;
        }
        
        return $this;
    }
    
    /**
     * Removes an inhibitor.
     * @param callable  $inhibitor
     * @return $this
     */
    function removeInhibitor(callable $inhibitor) {
        $key = \array_search($inhibitor, $this->inhibitors);
        if($key !== false) {
            unset($this->inhibitors[$key]);
        }
        
        return $this;
    }
    
    /**
     * Handles an incoming message.
     * @param \CharlotteDunois\Yasmin\Models\Message       $message
     * @param \CharlotteDunois\Yasmin\Models\Message|null  $oldMessage
     * @return \React\Promise\Promise
     */
    function handleMessage(\CharlotteDunois\Yasmin\Models\Message $message, \CharlotteDunois\Yasmin\Models\Message $oldMessage = null) {
        return (new \React\Promise\Promise(function (callable $resolve) use ($message, $oldMessage) {
            try {
                if($this->shouldHandleMessage($message, $oldMessage) === false) {
                    return $resolve();
                }
                
                $cmdMessage = null;
                $oldCmdMessage = null;
                
                if($oldMessage !== null) {
                    $oldCmdMessage = $this->results->get($oldMessage->id);
                    if($oldCmdMessage === null && !$this->client->getOption('nonCommandEditable')) {
                        return $resolve();
                    }
                    
                    $cmdMessage = $this->parseMessage($message);
                    if($cmdMessage && $oldCmdMessage) {
                        $cmdMessage->setResponses($oldCmdMessage->responses, $oldCmdMessage->responsePositions);
                    }
                } else {
                    $cmdMessage = $this->parseMessage($message);
                }
                
                if($cmdMessage) {
                    $this->inhibit($cmdMessage)->then(function () use ($message, $oldMessage, $cmdMessage, $resolve) {
                        if($cmdMessage->command) {
                            if($cmdMessage->command->isEnabledIn($message->guild)) {
                                $cmdMessage->run()->then(function ($responses = null) use ($message, $oldMessage, $cmdMessage, $resolve) {
                                    if($responses !== null && !\is_array($responses)) {
                                        $responses = array($responses);
                                    }
                                    
                                    $this->cacheCommandMessage($message, $oldMessage, $cmdMessage, $responses);
                                    $resolve();
                                })->done(null, array($this->client, 'handlePromiseRejection'));
                            } else {
                                $message->reply('The command `'.$cmdMessage->command->name.'` is disabled.')->then(function ($response) use ($message, $oldMessage, $cmdMessage, $resolve) {
                                    $responses = array($response);
                                    $cmdMessage->finalize($responses);
                                    $this->cacheCommandMessage($message, $oldMessage, $cmdMessage, $responses);
                                    $resolve();
                                })->done(null, array($this->client, 'handlePromiseRejection'));
                            }
                        } else {
                            $this->client->emit('unknownCommand', $cmdMessage);
                            if(((bool) $this->client->getOption('unknownCommandResponse'))) {
                                $message->reply('Unknown command. Use '.\CharlotteDunois\Livia\Commands\Command::anyUsage('help'))->then(function ($response) use ($message, $oldMessage, $cmdMessage, $resolve) {
                                    $responses = array($response);
                                    $cmdMessage->finalize($responses);
                                    $this->cacheCommandMessage($message, $oldMessage, $cmdMessage, $responses);
                                    $resolve();
                                })->done(null, array($this->client, 'handlePromiseRejection'));
                            }
                        }
                    }, function ($inhibited) use ($message, $oldMessage, $cmdMessage, $resolve) {
                        if(!\is_array($inhibited)) {
                            $inhibited = array($inhibited, null);
                        }
                        
                        $this->client->emit('commandBlocked', $cmdMessage, $inhibited[0]);
                        
                        if(!($inhibited[1] instanceof \React\Promise\PromiseInterface)) {
                            $inhibited[1] = \React\Promise\resolve($inhibited[1]);
                        }
                        
                        $inhibited[1]->then(function ($responses) use ($message, $oldMessage, $cmdMessage, $resolve) {
                            if($responses !== null) {
                                $responses = array($responses);
                            }
                            
                            $cmdMessage->finalize($responses);
                            $this->cacheCommandMessage($message, $oldMessage, $cmdMessage, $responses);
                            $resolve();
                        })->done(null, array($this->client, 'handlePromiseRejection'));
                    })->done(null, array($this->client, 'handlePromiseRejection'));
                } elseif($oldCmdMessage) {
                    $oldCmdMessage->finalize(null);
                    if(!$this->client->getOption('nonCommandEditable')) {
                        $this->results->delete($message->id);
                    }
                    
                    $this->cacheCommandMessage($message, $oldMessage, $cmdMessage, array());
                    $resolve();
                }
            } catch(\Throwable $error) {
                $this->client->emit('error', $error);
                throw $error;
            } catch(\Exception $error) {
                $this->client->emit('error', $error);
                throw $error;
            } catch(\ErrorException $error) {
                $this->client->emit('error', $error);
                throw $error;
            }
        }));
    }
    
    /**
     * Check whether a message should be handled.
     * @param \CharlotteDunois\Yasmin\Models\Message       $message
     * @param \CharlotteDunois\Yasmin\Models\Message|null  $oldMessage
     * @return bool
     */
    protected function shouldHandleMessage(\CharlotteDunois\Yasmin\Models\Message $message, \CharlotteDunois\Yasmin\Models\Message $oldMessage = null) {
        if($message->author->bot || $message->author->id === $this->client->user->id) {
            return false;
        }
        
        // Ignore messages from users that the bot is already waiting for input from
        if(\in_array($message->author->id.$message->channel->id, $this->awaiting)) {
            return false;
        }
        
        if($oldMessage !== null && $message->content === $oldMessage->content) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Inhibits a command message. Resolves with false or array (reason, ?response (Promise (-> Message), Message instance or null)).
     * @param \CharlotteDunois\Livia\CommandMessage  $message
     * @return \React\Promise\Promise
     */
    protected function inhibit(\CharlotteDunois\Livia\CommandMessage $message) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message) {
            $promises = array();
            
            foreach($this->inhibitors as $inhib) {
                $inhibited = $inhib($message);
                if(!($inhibited instanceof \React\Promise\PromiseInterface)) {
                    if($inhibited === false) {
                        $inhibited = \React\Promise\resolve($inhibited);
                    } else {
                        $inhibited = \React\Promise\reject($inhibited);
                    }
                }
                
                $promises[] = $inhibited;
            }
            
            \React\Promise\all($promises)->then(function ($values) use ($resolve, $reject) {
                foreach($values as $value) {
                    if($value !== false) {
                        return $reject($value);
                    }
                }
                
                $resolve();
            }, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
    
    /**
     * Caches a command message to be editable.
     * @param \CharlotteDunois\Yasmin\Models\Message         $message     Triggering message.
     * @param \CharlotteDunois\Yasmin\Models\Message|null    $oldMessage  Triggering message's old version.
     * @param \CharlotteDunois\Livia\CommandMessage|null     $cmdMsg      Command message to cache.
     * @param \CharlotteDunois\Yasmin\Models\Message[]|null  $responses   Responses to the message.
     */
    protected function cacheCommandMessage($message, $oldMessage, $cmdMsg, $responses) {
        $duration = (int) $this->client->getOption('commandEditableDuration', 0);
        
        if($duration <= 0 || $cmdMsg === null) {
            return;
        }
        
        if($responses !== null) {
            $this->results->set($message->id, $cmdMsg);
            if($oldMessage === null) {
                $this->client->addTimer($duration, function () use ($message) {
                    $this->results->delete($message->id);
                }, true);
            }
        } else {
            $this->results->delete($message->id);
        }
    }
    
    /**
     * Parses a message to find details about command usage in it.
     * @param \CharlotteDunois\Yasmin\Models\Message  $message
     * @return \CharlotteDunois\Livia\CommandMessage|null
     */
    protected function parseMessage(\CharlotteDunois\Yasmin\Models\Message $message) {
        // Find the command to run by patterns
        foreach($this->client->registry->commands as $command) {
            if($command->patterns === null) {
                continue;
            }
            
            foreach($command->patterns as $ptrn) {
                \preg_match($ptrn, $message->content, $matches);
                if(!empty($matches)) {
                    return (new \CharlotteDunois\Livia\CommandMessage($this->client, $message, $command, null, null, $matches));
                }
            }
        }
        
        $prefix = $this->client->getGuildPrefix($message->guild);
        if(empty($this->commandPatterns[$prefix])) {
            $this->buildCommandPattern($prefix);
        }
        
        $cmdMessage = $this->matchDefault($message, $this->commandPatterns[$prefix], 2);
        if(!$cmdMessage && $message->guild === null) {
            $cmdMessage = $this->matchDefault($message, '/^([^\s]+)/i');
        }
        
        return $cmdMessage;
    }
    
    /**
     * Matches a message against a guild command pattern.
     * @param \CharlotteDunois\Yasmin\Models\Message  $message
     * @param string                                  $pattern           The pattern to match against.
     * @param int                                     $commandNameIndex  The index of the command name in the pattern matches.
     * @return \CharlotteDunois\Livia\CommandMessage|null
     */
    protected function matchDefault(\CharlotteDunois\Yasmin\Models\Message $message, string $pattern, int $commandNameIndex = 1) {
        \preg_match($pattern, $message->content, $matches);
        if(!empty($matches)) {
            $commands = $this->client->registry->findCommands($matches[$commandNameIndex], true);
            if(\count($commands) !== 1 || $commands[0]->defaultHandling === false) {
                return (new \CharlotteDunois\Livia\CommandMessage($this->client, $message, null));
            }
            
            $argString = \substr($message->content, (\strlen($matches[1]) + (!empty($matches[2]) ? \strlen($matches[2]) : 0)));
            return (new \CharlotteDunois\Livia\CommandMessage($this->client, $message, $commands[0], $argString));
        }
        
        return null;
    }
    
    /**
     * Creates a regular expression to match the command prefix and name in a message.
     * @param string|null  $prefix
     * @return string
     * @internal
     */
    function buildCommandPattern(string $prefix = null) {
        $pattern = '';
        if($prefix !== null) {
            $escapedPrefix = \preg_quote($prefix, '/');
            $pattern = '/^(<@!?'.$this->client->user->id.'>\s+(?:'.$escapedPrefix.'\s*)?|'.$escapedPrefix.'\s*)([^\s]+)/iu';
        } else {
            $pattern = '/^(<@!?'.$this->client->user->id.'>\s+)([^\s]+)/iu';
        }
        
        $this->commandPatterns[$prefix] = $pattern;
        
        $this->client->emit('debug', 'Built command pattern for prefix "'.$prefix.'": '.$pattern);
        return $pattern;
    }
}
