<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
            parent::__construct($client, array(
                'name' => 'help',
                'aliases' => array('commands'),
                'group' => 'utils',
                'description' => 'Displays a list of available commands, or detailed information for a specified command.',
                'details' => "The command may be part of a command name or a whole command name.\nIf it isn't specified, all available commands will be listed.",
                'examples' => array('help', 'help prefix'),
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 2,
                    'duration' => 3
                ),
                'args' => array(
                    array(
                        'key' => 'command',
                        'prompt' => 'Which command would you like to view the help for?',
                        'type' => 'string',
                        'default' => ''
                    )
                ),
                'guarded' => true
            ));
        }
        
        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern) {
            return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $args) {
                $groups = $this->client->registry->groups;
                $commands = (!empty($args['command']) ? $this->client->registry->findCommands($args['command'], false, $message->message) : $this->client->registry->commands->all());
                $showAll = (!empty($args['command']) && \strtolower($args['command']) === 'all');
                
                if(!empty($args['command']) && !$showAll) {
                    $countCommands = \count($commands);
                    
                    if($countCommands === 0) {
                        return $resolve($message->reply('Unable to identify command. Use '.$this->usage('', ($message->message->channel->type === 'dm' ? null : $this->client->getGuildPrefix($message->message->guild)), ($message->message->channel->type === 'dm' ? null : $this->client->user)).' to view the list of all commands.'));
                    }
                    
                    foreach($commands as $key => $cmd) {
                        if(!empty($cmd->ownerOnly) && $cmd->hasPermission($message) !== true) {
                            unset($commands[$key]);
                        }
                    }
                    
                    $countCommands = \count($commands);
                    
                    if($countCommands === 1) {
                        $command = $commands[0];
                        
                        $help = "__Command **{$command->name}**:__ {$command->description} ".($command->guildOnly ? '(Usable only in servers)' : '').PHP_EOL.PHP_EOL.
                                '**Format:** '.\CharlotteDunois\Livia\Commands\Command::anyUsage($command->name.(!empty($command->format) ? ' '.$command->format : '')).PHP_EOL;
                                
                        if(!empty($command->aliases)) {
                            $help .= PHP_EOL.'**Aliases:** '.\implode(', ', $command->aliases);
                        }
                        
                        $help .= PHP_EOL."**Group:** {$command->group->name} (`{$command->groupID}:{$command->name}`)";
                        
                        if(!empty($command->details)) {
                            $help .= PHP_EOL.'**Details:** '.$command->details;
                        }
                        
                        if(!empty($command->examples)) {
                            $help .= PHP_EOL.'**Examples:**'.PHP_EOL.\implode(PHP_EOL, $command->examples);
                        }
                        
                        $message->direct($help)->otherwise(function () use ($message) {
                            return $message->reply('Unable to send you the help DM. You probably have DMs disabled.');
                        })->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
                    } elseif($countCommands > 15) {
                        $resolve($message->reply('Multiple commands found. Please be more specific.'));
                    } elseif($countCommands > 1) {
                        $resolve($message->reply(\CharlotteDunois\Livia\Utils\DataHelpers::disambiguation($commands, 'commands', 'name')));
                    }
                } else {
                    $help = 'To run a command in '.($message->message->guild !== null ? $message->message->guild->name : 'any server').', use '.
                            \CharlotteDunois\Livia\Commands\Command::anyUsage('command', $this->client->getGuildPrefix($message->message->guild), $this->client->user).
                            '. For example, '.
                            \CharlotteDunois\Livia\Commands\Command::anyUsage('prefix', $this->client->getGuildPrefix($message->message->guild), $this->client->user).'.'.PHP_EOL.
                            'To run a command in this DM, simply use '.\CharlotteDunois\Livia\Commands\Command::anyUsage('command').' with no prefix.'.PHP_EOL.PHP_EOL.
                            'Use '.$this->usage('<command>', null, null).' to view detailed information about a specific command.'.PHP_EOL.
                            'Use '.$this->usage('all', null, null).' to view a list of *all* commands, not just available ones.'.PHP_EOL.PHP_EOL.
                            '__**'.($showAll ? 'All commands' : 'Available commands in '.($message->message->guild !== null ? $message->message->guild->name : 'this DM')).'**__'.PHP_EOL.PHP_EOL.
                            \implode(PHP_EOL.PHP_EOL, \array_map(function ($group) use ($message, $showAll) {
                                $cmds = ($showAll ? $group->commands->filter(function ($cmd) use ($message) {
                                    return (!$cmd->ownerOnly || $this->client->isOwner($message->author));
                                }) : $group->commands->filter(function ($cmd) use ($message) {
                                    return $cmd->isUsable($message);
                                }));
                                
                                return "__{$group->name}__".PHP_EOL.\implode(PHP_EOL, $cmds->map(function ($cmd) {
                                    return "**{$cmd->name}:** {$cmd->description}";
                                })->all());
                            }, ($showAll ? $groups->filter(function ($group) use ($message) {
                                foreach($group->commands as $cmd) {
                                    if(!$cmd->ownerOnly || $this->client->isOwner($message->author)) {
                                        return true;
                                    }
                                }
                                
                                return false;
                            })->all() : $groups->filter(function ($group) use ($message) {
                                foreach($group->commands as $cmd) {
                                    if($cmd->isUsable($message)) {
                                        return true;
                                    }
                                }
                                
                                return false;
                            })->all())));
                    
                    $message->direct($help, array('split' => true))->otherwise(function () use ($message) {
                        return $message->reply('Unable to send you the help DM. You probably have DMs disabled.');
                    })->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
                }
            }));
        }
    });
};
