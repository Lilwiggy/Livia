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
 * Handles registration and searching of commands and groups.
 *
 * @property \CharlotteDunois\Livia\LiviaClient        $client        The client which initiated the instance.
 * @property \CharlotteDunois\Yasmin\Utils\Collection  $commands      Registered commands, mapped by their name.
 * @property string|null                               $commandsPath  Fully resolved path to the bot's commands directory.
 * @property \CharlotteDunois\Yasmin\Utils\Collection  $groups        Registered command groups, mapped by their name.
 * @property \CharlotteDunois\Yasmin\Utils\Collection  $types         Registered argument types, mapped by their name.
 */
class CommandRegistry {
    protected $client;
    
    protected $commands;
    protected $commandsPath;
    protected $groups;
    protected $types;
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        $this->client = $client;
        
        $this->commands = new \CharlotteDunois\Yasmin\Utils\Collection();
        $this->groups = new \CharlotteDunois\Yasmin\Utils\Collection();
        $this->types = new \CharlotteDunois\Yasmin\Utils\Collection();
    }
    
    /**
     * @internal
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception('Unknown property \CharlotteDunois\Livia\CommandRegistry::'.$name);
    }
    
    /**
     * Finds all commands that match the search string.
     * @param string                                       $searchString
     * @param bool                                         $exact         Whether the search should be exact.
     * @param \CharlotteDunois\Yasmin\Models\Message|null  $message       The message to check usability against.
     * @return \CharlotteDunois\Livia\Commands\Command[]
     */
    function findCommands(string $searchString, bool $exact = false, \CharlotteDunois\Yasmin\Models\Message $message = null) {
        $parts = array();
        $searchString = \strtolower($searchString);
        
        if(\strpos($searchString, ':') !== false) {
            $parts = \explode(':', $searchString);
            $searchString = \array_pop($parts);
        }
        
        $matches = array();
        foreach($this->commands as $command) {
            if($exact) {
                if(!empty($parts[0]) && $parts[0] === $command->groupID && ($command->name === $searchString || \in_array($searchString, $command->aliases)) && ($message === null || $command->hasPermission($message) === true)) {
                    return array($command);
                }
                
                if(($command->name === $searchString || \in_array($searchString, $command->aliases)) && ($message === null || $command->hasPermission($message) === true)) {
                    $matches[] = $command;
                }
            } else {
                if(!empty($parts[0]) && $parts[0] === $command->groupID && \stripos($command->name, $searchString) !== false && ($message === null || $command->hasPermission($message) === true)) {
                    return array($command);
                }
                
                if(\stripos($command->name, $searchString) !== false && ($message === null || $command->hasPermission($message) === true)) {
                    $matches[] = $command;
                }
            }
        }
        
        if($exact) {
            return $matches;
        }
        
        foreach($matches as $command) {
            if($command->name === $searchString || \in_array($searchString, $command->aliases)) {
                return array($command);
            }
        }
        
        return $matches;
    }
    
    /**
     * Finds all commands that match the search string.
     * @param string   $searchString
     * @param bool     $exact         Whether the search should be exact.
     * @return \CharlotteDunois\Livia\Commands\CommandGroup[]
     */
    function findGroups(string $searchString, bool $exact = false) {
        $searchString = \strtolower($searchString);
        if(\strpos($searchString, ':') !== false) {
            $parts = \explode(':', $searchString);
            $searchString = \array_pop($parts);
        }
        
        $matches = array();
        foreach($this->groups as $group) {
            if($exact) {
                if($group->id === $searchString || \strtolower($group->name) === $searchString) {
                    $matches[] = $group;
                }
            } else {
                if(\stripos($group->id, $searchString) !== false || \stripos($group->name, $searchString) !== false) {
                    $matches[] = $group;
                }
            }
        }
        
        if($exact) {
            return $matches;
        }
        
        foreach($matches as $group) {
            if($group->id === $searchString || \strtolower($group->name) === $searchString) {
                return array($group);
            }
        }
        
        return $matches;
    }
    
    /**
	 * Resolves a given command, command name or command message to the command.
	 * @param string|\CharlotteDunois\Livia\Commands\Command|\CharlotteDunois\Livia\CommandMessage  $resolvable
	 * @return \CharlotteDunois\Livia\Commands\Command
     * @throws \Exception
	 */
    function resolveCommand($resolvable) {
        if($resolvable instanceof \CharlotteDunois\Livia\Commands\Command) {
            return $resolvable;
        }
        
        if($resolvable instanceof \CharlotteDunois\Livia\CommandMessage) {
            return $resolvable->command;
        }
        
        $commands = $this->findCommands($resolvable, true);
        if(\count($commands) === 1) {
            return $commands[0];
        }
        
        throw new \Exception('Unable to resolve command');
    }
    /**
	 * Resolves a given commandgroup, command group name or command message to the command group.
	 * @param string|\CharlotteDunois\Livia\Commands\CommandGroup|\CharlotteDunois\Livia\CommandMessage  $resolvable
	 * @return \CharlotteDunois\Livia\Commands\CommandGroup
     * @throws \Exception
	 */
    function resolveGroup($resolvable) {
        if($resolvable instanceof \CharlotteDunois\Livia\Commands\CommandGroup) {
            return $resolvable;
        }
        
        if($resolvable instanceof \CharlotteDunois\Livia\CommandMessage) {
            return $resolvable->command->group;
        }
        
        $groups = $this->findGroups($resolvable, true);
        if(\count($groups) === 1) {
            return $groups[0];
        }
        
        throw new \Exception('Unable to resolve command group');
    }
    
    /**
     * Registers a command. Emits a commandRegister event.
     * @param string|\CharlotteDunois\Livia\Commands\Command  $command  The full qualified command name (groupID:name) or an initiated instance of it.
     * @return $this
     * @throws \Exception
     */
    function registerCommand(...$command) {
        foreach($command as $cmd) {
            if(!($cmd instanceof \CharlotteDunois\Livia\Commands\Command)) {
                $cmd = $this->handleCommandSpacing($cmd);
                $cmd = $cmd($this->client);
            }
            
            $this->commands->set($cmd->name, $cmd);
            
            $group = $this->resolveGroup($cmd->groupID);
            if($group) {
                $group->commands->set($cmd->name, $cmd);
            }
            
            $this->client->emit('debug', 'Registered command '.$cmd->groupID.':'.$cmd->name);
            $this->client->emit('commandRegister', $cmd, $this);
        }
        
        return $this;
    }
    
    /**
     * Registers all commands in a directory. The path gets saved as commands path. Emits a commandRegister event.
     * @param string        $path
     * @param bool|string   $ignoreSameLevelFiles  Ignores files in the specified directory and only includes files in sub directories. As string it will ignore the file if the filename matches with the string.
     * @return $this
     * @throws \Exception
     */
    function registerCommandsIn(string $path, bool $ignoreSameLevelFiles = false) {
        $path = \realpath($path);
        if(!$path) {
            throw new \Exception('Invalid path specified');
        }
        
        $this->commandsPath = $path;
        $files = \CharlotteDunois\Livia\Utils\FileHelpers::recursiveFileSearch($path, '*.php');
        
        foreach($files as $file) {
            if($ignoreSameLevelFiles === true) {
                $filepath = \ltrim(\str_replace(array($path, '\\'), array('', '/'), $file), '/');
                if(\substr_count($filepath, '/') === 0) {
                    continue;
                }
            } elseif(!empty($ignoreSameLevelFiles) && \stripos($file, $ignoreSameLevelFiles) !== false) {
                continue;
            }
            
            $command = include($file);
            $cmd = $command($this->client);
            
            if(!($cmd instanceof \CharlotteDunois\Livia\Commands\Command)) {
                throw new \Exception($name.' is not an instance of Command');
            }
            
            $this->commands->set($cmd->name, $cmd);
            
            $group = $this->resolveGroup($cmd->groupID);
            $group->commands->set($cmd->name, $cmd);
            
            $this->client->emit('debug', 'Registered command '.$cmd->groupID.':'.$cmd->name);
            $this->client->emit('commandRegister', $cmd, $this);
        }
        
        return $this;
    }
    
    /**
     * Registers a group.
     * @param \CharlotteDunois\Livia\Commands\CommandGroup|array  $group  An instance of CommandGroup or an associative array ('id', 'name')
     * @return $this
     * @throws \Exception
     */
    function registerGroup(...$group) {
        foreach($group as $gr) {
            $oldGr = $gr;
            
            if(!($gr instanceof \CharlotteDunois\Livia\Commands\CommandGroup)) {
                $gr = new \CharlotteDunois\Livia\Commands\CommandGroup($this->client, $gr['id'], $gr['name'], (bool) ($gr['guarded'] ?? false));
            }
            
            if(!($gr instanceof \CharlotteDunois\Livia\Commands\CommandGroup)) {
                throw new \Exception($oldGr.' is not an instance of CommandGroup');
            }
            
            $this->groups->set($gr->id, $gr);
            
            $this->client->emit('debug', 'Registered group '.$gr->id);
            $this->client->emit('groupRegister', $gr, $this);
        }
        
        return $this;
    }
    
    /**
     * Registers a type.
     * @param \CharlotteDunois\Livia\Types\ArgumentType|string  $type  The full qualified class name or an initiated instance of it.
     * @return $this
     * @throws \Exception
     */
    function registerType(...$type) {
        foreach($type as $t) {
            $oldT = $t;
            
            if(!($t instanceof \CharlotteDunois\Livia\Types\ArgumentType)) {
                $t = new $t($this->client);
            }
            
            if(!($t instanceof \CharlotteDunois\Livia\Types\ArgumentType)) {
                throw new \Exception($oldT.' is not an instance of Type');
            }
            
            $this->types->set($t->id, $t);
            
            $this->client->emit('debug', 'Registered type '.$t->id);
            $this->client->emit('typeRegister', $t, $this);
        }
        
        return $this;
    }
    
    /**
     * Registers all types in a directory.
     * @param string       $path
     * @param bool|string  $ignoreSameLevelFiles  Ignores files in the specified directory and only includes files in sub directories. As string it will ignore the file if the filename matches with the string.
     * @return $this
     * @throws \Exception
     */
    function registerTypesIn(string $path, $ignoreSameLevelFiles = false) {
        $path = \realpath($path);
        if(!$path) {
            throw new \Exception('Invalid path specified');
        }
        
        $files = \CharlotteDunois\Livia\Utils\FileHelpers::recursiveFileSearch($path, '*.php');
        foreach($files as $file) {
            if($ignoreSameLevelFiles === true) {
                $filepath = \ltrim(\str_replace(array($path, '\\'), array('', '/'), $file), '/');
                if(\substr_count($filepath, '/') === 0) {
                    continue;
                }
            } elseif(!empty($ignoreSameLevelFiles)) {
                $filepath = \ltrim(\str_replace(array($path, '\\'), array('', '/'), $file), '/');
                if(\stripos($filepath, $ignoreSameLevelFiles) === 0) {
                    continue;
                }
            }
            
            $code = \file_get_contents($file);
            
            preg_match('/namespace(.*?);/i', $code, $matches);
            if(empty($matches[1])) {
                $this->client->emit('error', $file.' is not a valid type file');
            }
            
            $namespace = \trim($matches[1]);
            
            preg_match('/class(.*?){/i', $code, $matches);
            if(empty($matches[1])) {
                $this->client->emit('error', $file.' is not a valid type file');
            }
            
            $name = \trim(\explode('implements', \explode('extends', $matches[1])[0])[0]);
            $fqn = '\\'.$namespace.'\\'.$name;
            
            $type = new $fqn($this->client);
            $this->types->set($type->id, $type);
            
            $this->client->emit('debug', 'Registered type '.$type->id);
            $this->client->emit('typeRegister', $type, $this);
        }
        
        return $this;
    }
    
    /**
     * Registers the default argument types, groups, and commands.
     */
    function registerDefaults() {
        $this->registerDefaultTypes();
        $this->registerDefaultGroups();
        $this->registerDefaultCommands();
    }
    
    /**
     * Registers the default commands.
     */
    function registerDefaultCommands() {
        $this->registerCommandsIn(__DIR__.'/Commands', true);
    }
    
    /**
     * Registers the default command groups.
     */
    function registerDefaultGroups() {
        $this->registerGroup(
            (new \CharlotteDunois\Livia\Commands\CommandGroup($this->client, 'commands', 'Commands', true)),
            (new \CharlotteDunois\Livia\Commands\CommandGroup($this->client, 'utils', 'Utilities', true))
        );
    }
    
    /**
     * Registers the default argument types.
     */
    function registerDefaultTypes() {
        $this->registerTypesIn(__DIR__.'/Types', 'ArgumentType.php');
    }
    
    /**
     * Reregisters a command. Emits a commandReregister event.
     * @param \CharlotteDunois\Livia\Commands\Command|string  $command     The full qualified command name (groupID:name) or an initiated instance of it.
     * @param \CharlotteDunois\Livia\Commands\Command         $oldCommand
     * @throws \Exception
     */
    function reregisterCommand($command, \CharlotteDunois\Livia\Commands\Command $oldCommand) {
        $oldCommand->group->commands->delete($oldCommand->name);
        $this->commands->delete($oldCommand->name);
        
        if(!($command instanceof \CharlotteDunois\Livia\Commands\Command)) {
            $command = $this->handleCommandSpacing($command);
            $command = $command($this->client);
        }
        
        $this->commands->set($command->name, $command);
        $command->group->commands->set($command->name, $command);
        
        $this->client->emit('debug', 'Reregistered command '.$command->groupID.':'.$command->name);
        $this->client->emit('commandReregister', $command, $oldCommand, $this);
    }
    
    /**
     * Unregisters a command. Emits a commandUnregister event.
     * @param \CharlotteDunois\Livia\Commands\Command  $command
     * @throws \Exception
     */
    function unregisterCommand(\CharlotteDunois\Livia\Commands\Command $command) {
        $group = $this->resolveGroup($command->groupID);
        $group->commands->delete($command->name);
        $this->commands->delete($command->name);
        
        $this->client->emit('debug', 'Unregistered command '.$command->groupID.':'.$command->name);
        $this->client->emit('commandUnregister', $command, $this);
    }
    
    /**
	 * Resolves a given group ID and command name to the path.
	 * @param string  $groupID
     * @param string  $command
	 * @return string
     * @throws \Exception
	 */
    function resolveCommandPath(string $groupID, string $command) {
        $paths = array(__DIR__.'/Commands/'.\ucfirst($groupID), $this->commandsPath.'/'.\ucfirst($groupID));
        
        foreach($paths as $path) {
            $file = $path.'/'.\ucfirst($command).'.php';
            if(file_exists($file)) {
                return $file;
            }
        }
        
        throw new \Exception('Unable to resolve command path');
    }
        
    protected function handleCommandSpacing(string $command) {
        $commanddot = \explode(':', $command);
        if(\count($commanddot) === 2) {
            $command = $this->resolveCommandPath($commanddot[0], $commanddot[1]);
            $cmd = include($command);
            return $cmd;
        }
        
        $command = \realpath($command);
        if($command !== false) {
            $cmd = include($command);
            return $cmd;
        }
        
        throw new \Exception('Unable to resolve command');
    }
}
