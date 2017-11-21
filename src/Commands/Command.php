<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Commands;


/**
 * A command that can be run in a client.
 *
 * @property \CharlotteDunois\Livia\CommandClient               $client             The client which initiated the instance.
 * @property string                                             $name               The name of the command.
 * @property string[]                                           $aliases            Aliases of the command.
 * @property \CharlotteDunois\Livia\Commands\CommandGroup|null  $group              The group the command belongs to, assigned upon registration.
 * @property string                                             $groupID            ID of the command group the command is part of.
 * @property string                                             $description        A short description of the command.
 * @property string|null                                        $details            A longer description of the command.
 * @property string                                             $format             Usage format string of the command.
 * @property string[]                                           $examples           Examples of and for the command.
 * @property bool                                               $guildOnly          Whether the command can only be triggered in a guild channel.
 * @property bool                                               $ownerOnly          Whether the command can only be triggered by the bot owner (requires default hasPermission method).
 * @property string[]|null                                      $clientPermissions  An array containing the required permissions for the client user to make the command work.
 * @property string[]|null                                      $userPermissions    An array containing the required permissions for the user.
 * @property bool                                               $nsfw               Whether the command can only be run in NSFW channels.
 * @property array                                              $throttling         Options for throttling command usages.
 * @property bool                                               $defaultHandling    Whether the command gets handled normally.
 * @property array                                              $args               An array containing the command arguments.
 * @property int|double                                         $argsPromptLimit    How many times the user gets prompted for an argument.
 * @property bool                                               $argsSingleQuotes   Whether single quotes are allowed to encapsulate an argument.
 * @property string                                             $argsType           How the arguments are split when passed to the command's run method.
 * @property int                                                $argsCount          Maximum number of arguments that will be split.
 * @property string[]                                           $patterns           Regular expression triggers.
 * @property bool                                               $guarded            Whether the command is protected from being disabled.
 */
class Command {
    protected $client;
    
    protected $name;
    protected $aliases = array();
    protected $groupID;
    protected $description;
    protected $details;
    protected $format = '';
    protected $examples = array();
    protected $guildOnly = false;
    protected $ownerOnly = false;
    protected $clientPermissions;
    protected $userPermission;
    protected $nsfw = false;
    protected $throttling = array();
    protected $defaultHandling = true;
    protected $args = array();
    protected $argsPromptLimit = \INF;
    protected $argsSingleQuotes = true;
    protected $argsType = 'single';
    protected $argsCount = 0;
    protected $patterns = array();
    protected $guarded = false;
    
    protected $globalEnabled = true;
    protected $guildEnabled = array();
    
    protected $argsCollector;
    protected $throttles;
    
    /**
     * Constructs a new Command. Info is an array as following:
     *
     *  array(                                                                                                                             <br />
     *      'name' => string,                                                                                                              <br />
     *      'aliases' => string[], (optional)                                                                                              <br />
     *      'group' => string, (the ID of the command group)                                                                               <br />
     *      'description => string,                                                                                                        <br />
     *      'details' => string, (optional)                                                                                                <br />
     *      'format' => string, (optional)                                                                                                 <br />
     *      'examples' => string[], (optional)                                                                                             <br />
     *      'guildOnly' => bool, (defaults to false)                                                                                       <br />
     *      'ownerOnly' => bool, (defaults to false)                                                                                       <br />
     *      'clientPermissions' => string[], (optional)                                                                                    <br />
     *      'userPermissions' => string[], (optional)                                                                                      <br />
     *      'nsfw' => bool, (defaults to false)                                                                                            <br />
     *      'throttling' => array, (associative array of array('usages' => int, 'duration' => int) - duration in seconds, optional)        <br />
     *      'defaultHandling' => bool, (defaults to true)                                                                                  <br />
     *      'args' => array, ({@see \CharlotteDunois\Livia\Arguments\Argument}, optional)                                                  <br />
     *      'argsPromptLimit' => int|\INF, (optional)                                                                                      <br />
     *      'argsType' => string, (one of 'single' or 'multiple', defaults to 'single')                                                    <br />
     *      'argsCount' => int, (optional)                                                                                                 <br />
     *      'argsSingleQuotes' => bool, (optional)                                                                                         <br />
     *      'patterns' => string[], (Regular Expression strings, optional)                                                                 <br />
     *      'guarded' => bool, (defaults to false)                                                                                         <br />
     *  )
     *
     * @param \CharlotteDunois\Livia\CommandClient  $client
     * @param array                                 $info
     * @throws \InvalidArgumentException
     */
    function __construct(\CharlotteDunois\Livia\CommandClient $client, array $info) {
        $this->client = $client;
        
        if(empty($info['name']) || !\is_string($info['name'])) {
            throw new \InvalidArgumentException('Command name must be specified and must be a string');
        }
        if(\strtolower($info['name']) !== $info['name'] || \strpos($info['name'], ' ') !== false) {
            throw new \InvalidArgumentException('Command name must be lowercase, without any whitespaces');
        }
        
        if(empty($info['group']) || !\is_string($info['group'])) {
            throw new \InvalidArgumentException('Invalid command group specified');
        }
        
        if(empty($info['description']) || !\is_string($info['description'])) {
            throw new \InvalidArgumentException('Invalid command description specified');
        }
        
        $this->name = $info['name'];
        $this->groupID = $info['group'];
        $this->description = $info['description'];
        
        if(!empty($info['aliases']) && \is_array($info['aliases'])) {
            $this->aliases = $info['aliases'];
            
            foreach($this->aliases as $alias) {
                if(!\is_string($alias)) {
                    throw new \InvalidArgumentException('Command aliases must be an array of strings');
                }
                
                if(\strtolower($alias) !== $alias) {
                    throw new \InvalidArgumentException('Command aliases must be lowercase');
                }
            }
        }
        
        if(!empty($info['autoAliases'])) {
            if(\strpos($this->name, '-') !== false) {
                $this->aliases[] = \str_replace('-', '', $this->name);
            }
            
            foreach($this->aliases as $alias) {
                if(\strpos($alias, '-') !== false) {
                    $this->aliases[] = \str_replace('-', '', $alias);
                }
            }
        }
        
        if(!empty($info['details'])) {
            if(!\is_string($info['details'])) {
                throw new \InvalidArgumentException('Invalid command details specified');
            }
            
            $this->details = $info['details'];
        }
        
        if(!empty($info['format'])) {
            if(!\is_string($info['format'])) {
                throw new \InvalidArgumentException('Invalid command format specified');
            }
            
            $this->format = $info['format'];
        }
        
        if(!empty($info['examples']) && \is_array($info['examples'])) {
            $this->examples = $info['examples'];
            
            foreach($this->examples as $example) {
                if(!\is_string($example)) {
                    throw new \InvalidArgumentException('Command examples must be an array of strings');
                }
            }
        }
        
        $this->guildOnly = (bool) ($info['guildOnly'] ?? $this->guildOnly);
        $this->ownerOnly = (bool) ($info['ownerOnly'] ?? $this->ownerOnly);
        
        if(!empty($info['clientPermissions'])) {
            if(!\is_array($info['clientPermissions'])) {
                throw new \InvalidArgumentException('Client Permissions must be an array of strings');
            }
            
            $this->clientPermissions = $info['clientPermissions'];
        }
        
        if(!empty($info['userPermissions'])) {
            if(!\is_array($info['userPermissions'])) {
                throw new \InvalidArgumentException('User Permissions must be an array of strings');
            }
            
            $this->userPermissions = $info['userPermissions'];
        }
        
        $this->nsfw = (bool) ($info['nsfw'] ?? $this->nsfw);
        
        if(isset($info['throttling']) && \is_array($info['throttling'])) {
            if(empty($info['throttling']['usages']) || empty($info['throttling']['duration'])) {
                throw new \InvalidArgumentException('Throttling array is missing elements or its elements are empty');
            }
            
            if(!\is_int($info['throttling']['usages'])) {
                throw new \InvalidArgumentException('Throttling usages must be an integer');
            }
            
            if(!\is_int($info['throttling']['duration'])) {
                throw new \InvalidArgumentException('Throttling duration must be an integer');
            }
            
            $this->throttling = $info['throttling'];
        }
        
        $this->defaultHandling = (bool) ($info['defaultHandling'] ?? $this->defaultHandling);
        
        $this->args = $info['args'] ?? array();
        if(!empty($this->args)) {
            $this->argsCollector = new \CharlotteDunois\Livia\Arguments\ArgumentCollector($this->client, $this->args, $this->argsPromptLimit);
            
            if(empty($this->format)) {
                $this->format = \array_reduce(function ($prev, $arg) {
                    $wrapL = ($arg->default !== null ? '[' : '<');
                    $wrapR = ($arg->default !== null ? '[' : '<');
                    
                    return $prev.($prev ? ' ' : '').$wrapL.$arg->label.($arg->infinite ? '...' : '').$wrapR;
                }, $this->argsCollector->args);
            }
        }
        
        if(!empty($info['argsType']) && !in_array($info['argsType'], array('single', 'multiple'))) {
            throw new \InvalidArgumentException('Command argsType must be one of "single" or "multiple"');
        }
        
        if(isset($info['argsPromptLimit']) && $info['argsPromptLimit'] !== \INF && (!\is_int($info['argsPromptLimit']) || $info['argsPromptLimit'] <= 0)) {
            throw new \InvalidArgumentException('Command argsPromptLimit must be an integer (or INF) and greater than 0');
        }
        
        $this->argsSingleQuotes = (bool) ($info['argsSingleQuotes'] ?? $this->argsSingleQuotes);
        $this->argsType = $info['argsType'] ?? $this->argsType;
        $this->argsPromptLimit = $info['argsPromptLimit'] ?? $this->argsPromptLimit;
        
        if(isset($info['argsCount']) && $this->argsType === 'multiple' && ((int) $info['argsCount']) < 2) {
            throw new \InvalidArgumentException('Command argsCount must be at least 2');
        }
        
        $this->argsCount = $info['argsCount'] ?? $this->argsCount;
        
        if(!empty($info['patterns'])) {
            if(!\is_array($info['patterns'])) {
                throw new \InvalidArgumentException('Command patterns must be an array of strings');
            }
            
            $this->patterns = $info['patterns'];
            
            foreach($this->patterns as $pattern) {
                if(!\is_string($pattern)) {
                    throw new \InvalidArgumentException('Command patterns must be an array of strings');
                }
            }
        }
        
        $this->guarded = (bool) ($info['guarded'] ?? $this->guarded);
        
        $this->throttles = new \CharlotteDunois\Yasmin\Utils\Collection();
    }
    
    /**
     * @internal
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        switch($name) {
            case 'group':
                return $this->client->registry->resolveGroup($this->groupID);
            break;
        }
        
        throw new \Exception('Unknown property \CharlotteDunois\Livia\Commands\Command::'.$name);
    }
    
    /**
	 * Checks if the user has permission to use the command.
	 * @param \CharlotteDunois\Livia\CommandMessage  $message
	 * @param bool                                   $ownerOverride  Whether the bot owner(s) will always have permission.
	 * @return bool|string  Whether the user has permission, or an error message to respond with if they don't.
	 */
    function hasPermission(\CharlotteDunois\Livia\CommandMessage $message, bool $ownerOverride = true) {
        if($this->ownerOnly === false && !$this->userPermission) {
            return true;
        }
        
        if($ownerOverride && $this->client->isOwner($message->message->author)) {
            return true;
        }
        
        if($this->ownerOnly && ($ownerOverride || !$this->client->isOwner($message->message->author))) {
            return 'The command `'.$this->name.'` can only be used by the bot owner.';
        }
        
        // Ensure the user has the proper permissions
        if($message->channel->type === 'text' && !empty($this->userPermissions)) {
            $perms = $message->channel->permissionsFor($message->member);
            
            $missing = array();
            foreach($this->userPermissions as $perm) {
                if($perms->missing($perm)) {
                    $missing[] = $perm;
                }
            }
            
            if(\count($missing) > 0) {
                $this->client->emit('commandBlocked', $message, 'userPermissions');
                
                if(\count($missing) === 1) {
                    $msg = 'The command `'.$this->name.'` requires you to have the `'.$missing[0].'` permission.';
                } else {
                    $missing = \implode(', ', \array_map(function ($perm) {
                        return '`'.\CharlotteDunois\Yasmin\Models\Permissions::resolveToName($perm).'`';
                    }, $missing));
                    $msg = 'The `'.$this->name.'` command requires you to have the following permissions:'.PHP_EOL.$missing;
                }
                
                return $msg;
            }
        }
        
        return true;
    }
    
    /**
	 * Runs the command.
	 * @param \CharlotteDunois\Livia\CommandMessage $message      The message the command is being run for
	 * @param mixed[]                               $args         The arguments for the command, or the matches from a pattern. If args is specified on the command, thise will be the argument values object. If argsType is single, then only one string will be passed. If multiple, an array of strings will be passed. When fromPattern is true, this is the matches array from the pattern match.
	 * @param bool                                  $fromPattern  Whether or not the command is being run from a pattern match
	 * @return \React\Promise\Promise
	 */
    abstract function run(\CharlotteDunois\Livia\CommandMessage $message, array $args, bool $fromPattern);
    
    /**
     * Reloads the command.
     */
    function reload() {
        $class = \explode('\\', \get_class($this));
        $name = \array_pop($class);
        
        $this->client->registry->reregisterCommand($GLOBALS['OLD_NAMESPACE_'.\strtoupper($name)], $this);
    }
    
    /**
     * Unloads the command.
     */
    function unload() {
        $this->client->registry->unregisterCommand($this);
    }
    
    /**
	 * Creates/obtains the throttle object for a user, if necessary (owners are excluded).
	 * @param string  $userID
     * @return array|null
     * @internal
     */
    function &throttle(string $userID) {
        if($this->throttling === null || $this->client->isOwner($userID)) {
            return null;
        }
        
        if(!$this->throttles->has($userID)) {
            $this->throttles->set($userID, array(
                'start' => \time(),
                'usages' => 0,
                'timeout' => $this->client->addTimer($this->throttling['duration'], function () use ($userID) {
                    $this->throttles->delete($userID);
                }, true)
            ));
        }
        
        return $this->throttles->get($userID);
    }
    
    /**
	 * Enables or disables the command in a guild (or globally).
	 * @param string|\CharlotteDunois\Yasmin\Models\Guild|null  $guild  The guild instance or the guild ID.
	 * @param bool                                              $enabled
     * @throws \BadMethodCallException|\InvalidArgumentException
	 */
    function setEnabledIn($guild, bool $enabled) {
        if($guild !== null) {
            $guild = $this->client->guilds->resolve($guild);
        }
        
        if($this->guarded) {
            throw new \BadMethodCallException('The group is guarded');
        }
        
        if($guild !== null) {
            $this->guildEnabled[$guild->id] = $enabled;
        } else {
            $this->globalEnabled = $enabled;
        }
    }
    
    /**
	 * Checks if the command is enabled in a guild (or globally).
	 * @param string|\CharlotteDunois\Yasmin\Models\Guild|null  $guild  The guild instance or the guild ID.
     * @return bool
     * @throws \InvalidArgumentException
	 */
    function isEnabledIn($guild) {
        if($guild !== null) {
            $guild = $this->client->guilds->resolve($guild);
            return (empty($this->guildEnabled[$guild->id]) || $this->guildEnabled[$guild->id]);
        }
        
        return $this->globalEnabled;
    }
    
    /**
	 * Checks if the command is usable for a message.
	 * @param \CharlotteDunois\Yasmin\Models\Message|null  $message
	 * @return bool
	 */
    function isUsable(\CharlotteDunois\Yasmin\Models\Message $message = null) {
        if($message === null) {
            return $this->globalEnabled;
        }
        
        if($this->guildOnly && $message->guild === null) {
            return false;
        }
        
        return ($this->isEnabledIn($message->guild) && $this->hasPermission($message) === true);
    }
    
    /**
	 * Creates a usage string for the command.
	 * @param string                               $argString  A string of arguments for the command.
	 * @param string|null                          $prefix     Prefix to use for the prefixed command format.
	 * @param \CharlotteDunois\Yasmin\Models\User  $user       User to use for the mention command format.
	 * @return string
	 */
    function usage(string $argString, string $prefix = null, \CharlotteDunois\Yasmin\Models\User $user = $this->client->user) {
        if($prefix === null) {
            $prefix = $this->client->commandPrefix;
		}
        
        return self::anyUsage($argString, $prefix, $user);
    }
    
    /**
	 * Creates a usage string for any command.
	 * @param string                                    $command    A command + arguments string.
	 * @param string|null                               $prefix     Prefix to use for the prefixed command format.
	 * @param \CharlotteDunois\Yasmin\Models\User|null  $user       User to use for the mention command format.
	 * @return string
	 */
    static function anyUsage(string $command, string $prefix = null, \CharlotteDunois\Yasmin\Models\User $user = null) {
        $command = \str_replace(' ', "\u{00A0}", $command);
        
        if(empty($prefix) && $user === null) {
            return '`'.$command.'`';
        }
        
        $prStr = null;
        if(!empty($prefix)) {
            if($prefix[(\strlen($prefix) - 1)] !== ' ') {
                $prefix .= ' ';
            }
            
            $prefix = \str_replace(' ', "\u{00A0}", $prefix);
            $prStr = '`'.\CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown($prefix.$command).'`';
        }
        
        $meStr = null;
        if($user !== null) {
            $meStr = '`'.\CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown(\str_replace(' ', "\u{00A0}", $user->tag)."\u{00A0}".$command).'`';
        }
        
        return ($prStr ?? '').(!empty($prefix) && $user !== null ? ' or ' : '').($meStr ?? '');
    }
}
