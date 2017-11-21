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
 * A group for commands.
 *
 * @property \CharlotteDunois\Livia\CommandClient      $client         The client which initiated the instance.
 * @property string                                    $id             The ID of the group.
 * @property string                                    $name           The name of the group.
 * @property bool                                      $guarded        Whether this group is guarded against disabling.
 * @property \CharlotteDunois\Yasmin\Utils\Collection  $commands       The commands that the group contains.
 */
class CommandGroup {
    protected $client;
    
    protected $id;
    protected $name;
    protected $guarded;
    protected $commands;
    
    protected $globalEnabled = true;
    protected $guildEnabled = array();
    
    /**
     * Constructs a new Command Group.
     * @param \CharlotteDunois\Livia\CommandClient  $client
     * @param string                                $id
     * @param string                                $name
     * @param bool                                  $guarded
     * @param array|null                            $commands
     */
    function __construct(\CharlotteDunois\Livia\CommandClient $client, string $id, string $name, bool $guarded = false, array $commands = null) {
        $this->client = $client;
        
        $this->id = $id;
        $this->name = $name;
        $this->guarded = $guarded;
        
        $this->commands = new \CharlotteDunois\Yasmin\Utils\Collection();
        if(!empty($commands)) {
            foreach($commands as $command) {
                $this->commands->set($command->name, $command);
            }
        }
    }
    
    /**
     * @internal
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception('Unknown property \CharlotteDunois\Livia\Commands\CommandGroup::'.$name);
    }
    
    /**
	 * Enables or disables the group in a guild.
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
	 * Checks if the group is enabled in a guild.
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
     * Reloads all of the group's commands.
     */
    function reload() {
        foreach($this->commands as $command) {
            $command->reload();
        }
    }
}
