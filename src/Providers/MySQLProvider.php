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
 * Loads and stores settings associated with guilds in a MySQL database. Requires the composer package react/mysql.
 */
class MySQLProvider extends SettingProvider {
    protected $db;
    
    protected $listeners = array();
    protected $settings;
    
    /**
     * Constructs a new instance.
     * @param \React\EventLoop\LoopInterface  $loop
     * @param string                          $host
     * @param string                          $user
     * @param string                          $password
     * @param string                          $database
     * @param int                             $port
     */
    function __construct(\React\EventLoop\LoopInterface $loop, string $host, string $user, string $password, string $database, int $port = 3306) {
        $this->db = new React\MySQL\Connection($loop, array(
            'host' => $host,
            'user' => $user,
            'passwd' => $password,
            'dbname' => $database,
            'port' => $port
        ));
        
        $this->settings = new \CharlotteDunois\Yasmin\Utils\Collection();
        
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
        $this->listeners['commandRegister'] = function ($command) {
            foreach($this->settings as $guild => $settings) {
                if($guild !== 'global' && $this->client->guilds->has($guild) === false) {
                    continue;
                }
                
                $this->setupGuildCommand($guild, $command, $settings);
            }
        };
        $this->listeners['groupRegister'] = function ($group) {
            foreach($this->settings as $guild => $settings) {
                if($guild !== 'global' && $this->client->guilds->has($guild) === false) {
                    continue;
                }
                
                $this->setupGuildGroup($guild, $group, $settings);
            }
        };
    }
    
    /**
     * @inheritDoc
     * @return \React\Promise\Promise
     */
    function clear($guild) {
        $guild = $this->getGuildID($guild);
        
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($guild) {
            $this->runQuery('DELETE FROM `settings` WHERE `guild` = ?', array($guild))->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
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
            $this->settings->set($guild, &$settings);
            $this->runQuery('INSERT INTO `settings` (`guild`, `settings`) VALUES (?, ?)', array($guild, \json_encode($settings)))->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
    
    /**
     * @inheritDoc
     */
    function destroy() {
        foreach($this->listeners as $event => $listener) {
            $this->client->removeListener($event, $listener);
        }
    }
    
    /**
     * @inheritDoc
     */
    function init(\CharlotteDunois\Livia\CommandClient $client) {
        $this->client = $client;
        
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) {
            $promises = array();
            
            $promises[] = new \React\Promise\Promise(function (callable $resolve, $reject) {
                $this->db->connect(function ($reason) use ($resolve, $reject) {
                    if($reason !== null) {
                        $resolve();
                    } else {
                        $reject($reason);
                    }
                });
            });
            
            foreach($this->listeners as $event => $listener) {
                $this->client->on($event, $listener);
            }
            
            \React\Promise\all($promises)->then(function () use ($resolve, $reject) {
                $this->runQuery('CREATE TABLE IF NOT EXISTS `settings` (`guild` VARCHAR(20) NOT NULL, `settings` TEXT NOT NULL, PRIMARY KEY (`guild`))')->otherwise($reject)->done(null, array($this->client, 'handlePromiseRejection'));
                
                $this->runQuery('SELECT * FROM `settings`')->then(function ($command) use ($resolve) {
                    foreach($command->resultRows as $row) {
                        $settings = \json_decode($row['settings'], true);
                        if($settings === null) {
                            $this->client->emit('warn', 'MySQLProvider couldn\'t parse the settings stored for guild "'.$row['guild'].'". Error: '.\json_last_error_msg());
                            continue;
                        }
                        
                        $this->settings->set($row['guild'], $settings);
                        $this->setupGuild($row['guild']);
                    }
                    
                    $resolve();
                }, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
            }, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
    
    /**
     * @inheritDoc
     */
    function get($guild, string $key, $defaultValue = null) {
        $guild = $this->getGuildID($guild);
        
        if($this->settings->get($guild) === null) {
            throw new \BadMethodCallException('Settings of specified guild is not loaded');
        }
        
        $settings = $this->settings->get($guild);
        if(\array_key_exists($key, $settings)) {
            return $settings[$key];
        }
        
        return $defaultValue;
    }
    
    /**
     * @inheritDoc
     * @return \React\Promise\Promise
     */
    function remove($guild, string $key) {
        $guild = $this->getGuildID($guild);
        
        if($this->settings->get($guild) === null) {
            throw new \BadMethodCallException('Settings of specified guild is not loaded');
        }
        
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($guild, $key) {
            $settings = &$this->settings->get($guild);
            unset($settings[$key]);
        
            $this->runQuery('UPDATE `settings` SET `settings` = ? WHERE `guild` = ?', array(\json_encode($settings), $guild))->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
    
    /**
     * @inheritDoc
     * @return \React\Promise\Promise
     */
    function set($guild, string $key, $value) {
        $guild = $this->getGuildID($guild);
        
        if($this->settings->get($guild) === null) {
            throw new \BadMethodCallException('Settings of specified guild is not loaded');
        }
        
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($guild, $key, $value) {
            $settings = &$this->settings->get($guild);
            $settings[$key] = $value;
        
            $this->runQuery('UPDATE `settings` SET `settings` = ? WHERE `guild` = ?', array(\json_encode($settings), $guild))->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
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
            $this->create($guild, &$settings)->done(null, array($this->client, 'handlePromiseRejection'));
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
    
    /**
     * Runs a SQL query. Resolves with the Command instance.
     * @param string  $sql
     * @param array   $parameters  Parameters for the query - these get escaped
     * @return \React\Promise\Promise
     * @see https://github.com/bixuehujin/reactphp-mysql/blob/master/src/Command.php
     */
    function runQuery(string $sql, array $parameters = array()) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($sql, $parameters) {
            if(!empty($parameters)) {
                $query = new \React\MySQL\Query($sql);
                $query->bindParamsFromArray($parameters);
                $sql = $query->getSql();
            }
            
            $this->db->query($sql, function ($command) use ($resolve, $reject) {
                if($command->hasError()) {
                    return $reject($command->getError());
                }
                
                $resolve($command);
            });
        }));
    }
}
