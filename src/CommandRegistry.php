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
 * @property \CharlotteDunois\Livia\CommandClient      $client        The client which initiated the instance.
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
    function __construct(\CharlotteDunois\Livia\CommandClient $client) {
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
        $searchString = \strtolower($searchString);
        if(\strpos($searchString, ':') !== false) {
            $parts = \explode(':', $searchString);
            $searchString = \array_pop($parts);
        }
        
        $matches = array();
        foreach($this->commands as $command) {
            if($exact) {
                if(\strtolower($command->name) === $searchString && ($message === null || $command->hasPermission($message) === true)) {
                    $matches[] = $command;
                }
            } else {
                if(\stripos($command->name, $searchString) !== false && ($message === null || $command->hasPermission($message) === true)) {
                    $matches[] = $command;
                }
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
                if(\strtolower($group->id) === $searchString || \strtolower($group->name) === $searchString) {
                    $matches[] = $group;
                }
            } else {
                if(\stripos($group->id, $searchString) !== false || \stripos($group->name, $searchString) !== false) {
                    $matches[] = $group;
                }
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
     * Registers a command.
     * @param string|\CharlotteDunois\Livia\Commands\Command  $command  The full qualified class name or an initiated instance of it.
     * @return $this
     * @throws \Exception
     */
    function registerCommand(...$command) {
        foreach($command as $cmd) {
            if(!($cmd instanceof \CharlotteDunois\Livia\Commands\Command)) {
                $cmd = $this->handleCommandSpacing($cmd);
                $cmd = new $cmd($this);
            }
            
            $this->commands->set($cmd->name, $cmd);
            
            $group = $this->resolveGroup($command->groupID);
            if($group) {
                $group->commands->set($command->name, $command);
            }
            
            $this->client->emit('debug', 'Registered command '.$command->groupID.':'.$command->name);
        }
        
        return $this;
    }
    
    /**
     * Registers all commands in a directory.
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
        $files = \CharlotteDunois\Livia\FileHelpers::recursiveFileSearch($path, '*.php');
        
        foreach($files as $file) {
            if($ignoreSameLevelFiles === true) {
                $filepath = \ltrim(str_replace(array($path, '\\'), array('', '/'), $file), '/');
                if(\substr_count($filepath, '/') > 0) {
                    continue;
                }
            } elseif(!empty($ignoreSameLevelFiles) && \stripos($file, $ignoreSameLevelFiles) !== false) {
                continue;
            }
            
            $code = \file_get_contents($file);
            
            preg_match('/class(.*?){/i', $code, $matches);
            if(empty($matches[1])) {
                $this->client->emit('error', $file.' is not a valid command file');
            }
            
            $name = \trim(\explode('implements', \explode('extends', $matches[1])[0])[0]);
            $command = $this->handleCommandSpacingFile($name, $code);
            
            $cmd = new $command($this);
            
            if(!($cmd instanceof \CharlotteDunois\Livia\Commands\Command)) {
                throw new \Exception($name.' is not an instance of Command');
            }
            
            $this->commands->set($cmd->name, $cmd);
            
            $group = $this->resolveGroup($cmd->groupID);
            $group->commands->set($cmd->name, $cmd);
            
            $this->client->emit('debug', 'Registered command '.$command->groupID.':'.$command->name);
        }
        
        return $this;
    }
    
    /**
     *  Registers a group.
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
                $t = new $t($this);
            }
            
            if(!($t instanceof \CharlotteDunois\Livia\Types\ArgumentType)) {
                throw new \Exception($oldT.' is not an instance of Type');
            }
            
            $this->types->set($t->name, $t);
            $this->client->emit('debug', 'Registered type '.$t->name);
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
        
        $files = \CharlotteDunois\Livia\FileHelpers::recursiveFileSearch($path, '*.php');
        foreach($files as $file) {
            if($ignoreSameLevelFiles === true) {
                $filepath = \ltrim(str_replace(array($path, '\\'), array('', '/'), $file), '/');
                if(\substr_count($filepath, '/') > 0) {
                    continue;
                }
            } elseif(!empty($ignoreSameLevelFiles) && \stripos($file, $ignoreSameLevelFiles) !== false) {
                continue;
            }
            
            $code = \file_get_contents($file);
            
            preg_match('/namespace(.*?);/i', $code, $matches);
            if(empty($matches[1])) {
                $this->client->emit('error', $file.' is not a valid type file');
            }
            
            $namespace = \trim($matches[1]);
            $name = \trim(\explode('implements', \explode('extends', $matches[1])[0])[0]);
            $fqn = '\\'.$namespace.'\\'.$name;
            
            $type = new $fqn($this);
            $this->types->set($type->name, $type);
        }
        
        return $this;
    }
    
    /**
     * Registers the default argument types, groups, and commands.
     */
    function registerDefaults() {
        $this->registerDefaultGroups();
        $this->registerDefaultTypes();
        $this->registerDefaultCommands();
    }
    
    function registerDefaultCommands() {
        $this->registerCommandIn(__DIR__.'/Commands', true);
    }
    
    function registerDefaultGroups() {
        $this->registerGroup(
            (new \CharlotteDunois\Livia\Commands\CommandGroup($this->client, 'commands', 'Commands', true)),
            (new \CharlotteDunois\Livia\Commands\CommandGroup($this->client, 'utils', 'Utilities', true))
        );
    }
    
    function registerDefaultTypes() {
        $this->registerTypesIn(__DIR__.'/Types', 'ArgumentType.php');
    }
    
    /**
     * Reregisters a command (does not support changing name or group). Emits a commandReregister event.
     * @param \CharlotteDunois\Livia\Commands\Command|string  $command     The full qualified class name or an initiated instance of it.
     * @param \CharlotteDunois\Livia\Commands\Command         $oldCommand
     * @throws \Exception
     */
    function reregisterCommand($command, \CharlotteDunois\Livia\Commands\Command $oldCommand) {
        if(!($command instanceof \CharlotteDunois\Livia\Commands\Command)) {
            $command = $this->handleCommandSpacing($command);
            $command = new $command($this);
        }
        
        $this->commands->set($command->name, $command);
        $group = $this->resolveGroup($command->groupID);
        $group->commands->set($command->name, $command);
        
        $this->client->emit('commandReregister', $command, $oldCommand);
        $this->client->emit('debug', 'Reregistered command '.$command->groupID.':'.$command->name);
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
        
        $this->client->emit('commandUnregister', $command);
        $this->client->emit('debug', 'Unregistered command '.$command->groupID.':'.$command->name);
    }
        
    protected function handleCommandSpacing(string $command) {
        $reflector = new \ReflectionClass($command);
        $contents = \file_get_contents($reflector->getFileName());
        
        preg_match('/class(.*?){/i', $contents, $matches);
        if(empty($matches[1])) {
            throw new \Exception('Invalid command file');
        }
        
        $name = \trim(\explode('implements', \explode('extends', $matches[1])[0])[0]);
        return $this->handleCommandSpacingFile($name, $contents);
    }
    
    protected function handleCommandSpacingFile(string $name, string $code) {
        $timestamp = time();
        $namespace = 'CharlotteDunois\\Livia\\Commands\\Spacing\\'.$timestamp;
        $oldnamespace = "";
        
        $code = \explode("\n", \str_replace("\r", "", $code));
        foreach($code as $line => $lcode) {
            if(\stripos($lcode, '<?php') !== false) {
                unset($code[$line]);
            } elseif(\stripos($lcode, 'namespace') !== false) {
                $oldnamespace = \trim(\str_replace(array('namespace', ';'), '', $lcode));
                unset($code[$line]);
                break;
            }
        }
        
        $GLOBALS['OLD_NAMESPACE_'.\strtoupper($name)] = $oldnamespace;
        
        $php = <<<CMD
<?php
/**
 * Livia - Commands Namespacing
 */
namespace {$namespace};

{\implode(PHP_EOL, $code)}
CMD;
        
        $path = \sys_get_temp_dir().'/php-livia-command-'.$timestamp.'-'.$name.'.php';
        $state = \file_put_contents($path, $php);
        if($state === false) {
            throw new \Exception('Unable to write temporary class file');
        }
        
        include_once($path);
        return '\\'.$namespace.'\\'.$name;
    }
}
