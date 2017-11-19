<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Providers;

/**
 * Loads and stores settings associated with guilds using MySQLi. Requires mysqlnd.
 */
class MySQLiProvider extends SettingProvider {
    protected $db;
    protected $jobs;
    protected $listeners = array();
    protected $settings;
    
    function __construct(\mysqli $mysqli) {
        $this->db = $mysqli;
        
        $this->jobs = new \SplQueue();
        $this->settings = new \CharlotteDunois\Yasmin\Utils\Collection();
    }
    
    /**
     * @inheritDoc
     */
    function clear($guild) {
        
    }
    
    /**
     * Creates a new table row in the db for the guild.
     * @param string|\CharlotteDunois\Yasmin\Models\Guild  $guild
     * @param array                                        $settings
     * @return \React\Promise\Promise
     * @throws \InvalidArgumentException
     */
    function create($guild, array $settings = array()) {
        $guild = $this->getGuildID($guild);
        
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($guild, $settings) {
            
        }));
    }
    
    /**
     * @inheritDoc
     */
    function destroy() {
        
    }
    
    /**
     * @inheritDoc
     */
    function init(\CharlotteDunois\Livia\CommandClient $client) {
        $this->client = $client;
        
        
        $this->listeners['commandPrefixChange'] = function ($guild, $prefix) {
            $this->set($guild, 'commandPrefix', $prefix);
        };
        $this->listeners['commandStatusChange'] = function ($guild, $command, $enabled) {
            $this->set($guild, 'command-'.$command->name, $enabled);
        };
        $this->listeners['groupStatusChange'] = function ($guild, $group, $enabled) {
            $this->set($guild, 'group-'.$group->id, $enabled);
        };
        $this->listeners['guildCreate'] = function ($guild) {
            $this->setupGuild($guild);
        };
    }
    
    /**
     * @inheritDoc
     */
    function get($guild, string $key, $defaultValue = null) {
        
    }
    
    /**
     * @inheritDoc
     */
    function remove($guild, string $key) {
        
    }
    
    /**
     * @inheritDoc
     */
    function set($guild, string $key, $value) {
        
    }
    
    /**
     * Loads all settings for a guild.
     * @param string|\CharlotteDunois\Yasmin\Models\Guild  $guild
     * @internal
     */
    function setupGuild($guild) {
        $guild = $this->getGuildID($guild);
        
        $settings = &$this->settings->get($guild);
        if(!$settings) {
            $settings = array();
            $this->settings->set($guild, &$settings);
            
            $this->createGuild($guild, $settings);
        }
        
        if($guild === 'global' && \array_key_exists('commandPrefix', $settings)) {
            $this->client->setCommandPrefix($settings['commandPrefix']);
        }
        
        foreach($this->client->registry->commands as $command) {
            $this->setupGuildCommand($guild, $command, &$settings);
        }
        
        foreach($this->client->registry->groups as $group) {
            $this->setupGuildGroup($guild, $group, &$settings);
        }
    }
    
    /**
     * Sets up a command's status in a guild from the guild's settings.
     * @param string|\CharlotteDunois\Yasmin\Models\Guild  $guild
     * @param \CharlotteDunois\Livia\Commands\Command      $command
     * @param array                                        $settings
     * @internal
     */
    function setupGuildCommand($guild, \CharlotteDunois\Livia\Commands\Command $command, array &$settings) {
        if(!isset($settings['command-'.$command->name])) {
            return;
        }
        
        $command->setEnabledIn(($guild !== 'global' ? $guild : null), $settings['command-'.$command->name]);
    }
    
    /**
     * Sets up a group's status in a guild from the guild's settings.
     * @param string|\CharlotteDunois\Yasmin\Models\Guild   $guild
     * @param \CharlotteDunois\Livia\Commands\CommandGroup  $group
     * @param array                                         $settings
     * @internal
     */
    function setupGuildGroup($guild, \CharlotteDunois\Livia\Commands\CommandGroup $group, array &$settings) {
        if(!isset($settings['group-'.$group->id])) {
            return;
        }
        
        $group->setEnabledIn(($guild !== 'global' ? $guild : null), $settings['group-'.$group->id]);
    }
}
