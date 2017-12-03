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
 * The Command Client, the heart of the framework.
 *
 * @property \CharlotteDunois\Livia\CommandDispatcher                 $dispatcher     The client's command dispatcher.
 * @property \CharlotteDunois\Livia\CommandRegistry                   $registry       The client's command registry.
 * @property \CharlotteDunois\Livia\Providers\SettingProvider|null    $provider       The client's setting provider.
 * @property string|null                                              $commandPrefix  The global command prefix. {@see LiviaClient::setCommandPrefix}
 * @property \CharlotteDunois\Yasmin\Models\User[]                    $owners         Owners of the bot, set by the client option owners. If you simply need to check if a user is an owner of the bot, please use LiviaClient::isOwner instead. {@see \CharlotteDunois\Livia\LiviaClient:isOwner}
 */
class LiviaClient extends \CharlotteDunois\Yasmin\Client {
    protected $dispatcher;
    protected $registry;
    protected $provider;
    
    /**
     * Constructs a new Command Client. Additional available Client Options are as following:
     *
     *  array(                                                                                                                                         <br />
     *      'commandPrefix' => string|null, (Default command prefix, null means only mentions will trigger the handling, defaults to l$)               <br />
     *      'commandEditableDuration' => int, (Time in seconds that command messages should be editable, defaults to 30)                               <br />
     *      'nonCommandEditable' => bool, (Whether messages without commands can be edited to a command, defaults to true)                             <br />
     *      'unknownCommandResponse' => bool, (Whether the bot should respond to an unknown command, defaults to true)                                 <br />
     *      'owners' => string[], (array of user IDs)                                                                                                  <br />
     *      'invite' => string, (Invite URL to the bot's support server)                                                                               <br />
     *  )
     *
     * @param array                           $options  Any Client Options.
     * @param \React\EventLoop\LoopInterface  $loop
     */
    function __construct(array $options, \React\EventLoop\LoopInterface $loop = null) {
        if(!\array_key_exists('commandPrefix', $options)) {
            $options['commandPrefix'] = 'l$';
        } elseif($options['commandPrefix'] === null) {
            $options['commandPrefix'] = '';
        }
        
        if(empty($options['commandEditableDuration'])) {
            $options['commandEditableDuration'] = 30;
        }
        
        if(!isset($options['nonCommandEditable'])) {
            $options['nonCommandEditable'] = true;
        }
        
        if(!isset($options['unknownCommandResponse'])) {
            $options['unknownCommandResponse'] = true;
        }

        parent::__construct($options, $loop);
        
        $this->dispatcher = new \CharlotteDunois\Livia\CommandDispatcher($this);
        $this->registry = new \CharlotteDunois\Livia\CommandRegistry($this);
        
        $msgError = function ($error) {
            $this->emit('error', $error);
        };
        
        $this->on('message', function ($message) use ($msgError) {
            $this->dispatcher->handleMessage($message)->otherwise($msgError);
        });
        $this->on('messageUpdate', function ($message, $oldMessage) use ($msgError) {
            $this->dispatcher->handleMessage($message, $oldMessage)->otherwise($msgError);
        });
        
        if(!empty($options['owners'])) {
            $this->once('ready', function () use ($options) {
                if(!\is_array($options['owners'])) {
                    $options['owners'] = array($options['owners']);
                }
                
                foreach($options['owners'] as $owner) {
                    $this->fetchUser($owner)->otherwise(function ($error) use ($owner) {
                        $this->emit('warn', 'Unable to fetch owner '.$owner);
                        $this->emit('error', $error);
                    })->done(null, array($this, 'handlePromiseRejection'));
                }
            });
        }
    }
    
    /**
     * @internal
     */
    function __get($name) {
        switch($name) {
            case 'dispatcher':
            case 'registry':
            case 'provider':
                return $this->$name;
            break;
            case 'commandPrefix':
                return $this->options['commandPrefix'];
            break;
            case 'owners':
                $owners = array();
                foreach($this->options['owners'] as $owner) {
                    $owners[] = $this->users->get($owner);
                }
                
                return $owners;
            break;
        }
        
        return parent::__get($name);
    }
    
    /**
     * Sets the global command prefix. Null indicates that there is no default prefix, and only mentions will be used. Emits a commandPrefixChange event.
     * @param string|null  $prefix
     * @param bool         $fromProvider
     * @return $this
     * @throws \InvalidArgumentException
     */
    function setCommandPrefix($prefix, bool $fromProvider = false) {
        if(\is_string($prefix) && empty($prefix)) {
            throw new \InvalidArgumentException('Can not set an empty string as command prefix');
        }
        
        $this->options['commandPrefix'] = $prefix;
        
        if($fromProvider === false && $this->provider !== null) {
            if(empty($prefix)) {
                $this->provider->remove('global', 'commandPrefix')->done(null, array($this, 'handlePromiseRejection'));
            } else {
                $this->provider->set('global', 'commandPrefix', $prefix)->done(null, array($this, 'handlePromiseRejection'));
            }
        }
        
        $this->emit('commandPrefixChange', null, $prefix);
        return $this;
    }
    
    /**
     * Checks whether an user is an owner of the bot.
     * @param string|\CharlotteDunois\Yasmin\Models\User|\CharlotteDunois\Yasmin\Models\GuildMember  $user
     * @return bool
     */
    function isOwner($user) {
        if($user instanceof \CharlotteDunois\Yasmin\Models\User || $user instanceof \CharlotteDunois\Yasmin\Models\GuildMember) {
            $user = $user->id;
        }
        
        return (\in_array($user, $this->options['owners']));
    }
    
    /**
     * Sets the setting provider to use, and initializes it once the client is ready
     * @param \CharlotteDunois\Livia\Providers\SettingProvider  $provider
     * @return \React\Promise\Promise
     */
    function setProvider(\CharlotteDunois\Livia\Providers\SettingProvider $provider) {
        $this->provider = $provider;
        
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) {
            $classname = \get_class($this->provider);
            
            if($this->readyTimestamp !== null) {
                $this->emit('debug', 'Provider set to '.$classname.' - initializing...');
                $this->provider->init($this)->then($resolve, $reject)->done(null, array($this, 'handlePromiseRejection'));
                return;
            }
            
            $this->emit('debug', 'Provider set to '.$classname.' - will initialize once ready.');
            
            $this->once('ready', function () use ($resolve, $reject) {
                $this->emit('debug', 'Initializing provider...');
                $this->provider->init($this)->then($resolve, $reject)->done(null, array($this, 'handlePromiseRejection'));
            });
        }))->then(function () {
            $this->emit('debug', 'Provider finished initialization.');
        });
    }
    
    /**
     * Get the guild's prefix - or the default prefix. Null means only mentions.
     * @param \CharlotteDunois\Yasmin\Models\Guild|null  $guild
     * @return string|null
     */
    function getGuildPrefix(\CharlotteDunois\Yasmin\Models\Guild $guild = null) {
        if($guild !== null && $this->provider !== null) {
            try {
                $prefix = $this->provider->get($guild, 'commandPrefix', 404);
                if($prefix !== 404) {
                    return $prefix;
                }
            } catch(\BadMethodCallException $e) {
                return $this->commandPrefix;
            }
        }
        
        return $this->commandPrefix;
    }
    
    /**
     * Set the guild's prefix. An empty string means the command prefix will be used. Null means only mentions. Return value indicates if the prefix has been sent to the provider or there was no provider to set it.
     * @param \CharlotteDunois\Yasmin\Models\Guild|null  $guild
     * @param string|null                                $prefix
     * @return bool
     */
    function setGuildPrefix(\CharlotteDunois\Yasmin\Models\Guild $guild, string $prefix = null) {
        if($this->provider !== null) {
            if(\is_string($prefix) && empty($prefix)) {
                $this->provider->remove($guild, 'commandPrefix')->done(null, array($this, 'handlePromiseRejection'));
            } else {
                $this->provider->set($guild, 'commandPrefix', $prefix)->done(null, array($this, 'handlePromiseRejection'));
            }
            
            $this->emit('commandPrefixChange', $guild, $prefix);
            return true;
        }
        
        return false;
    }
    
    /**
     * @internal
     */
    function destroy(bool $destroyUtils = true) {
        return parent::destroy($destroyUtils)->then(function () {
            if($this->provider !== null) {
                return $this->provider->destroy();
            }
        });
    }
}
