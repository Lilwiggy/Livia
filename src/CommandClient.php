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
 * @property string|null                                              $commandPrefix  The global command prefix. {@see CommandClient::setCommandPrefix}
 * @property \CharlotteDunois\Yasmin\\Models\User[]                   $owners         Owners of the bot, set by the client option owners. If you simply need to check if a user is an owner of the bot, please instead use CommandClient::isOwner.
 */
class CommandClient extends \CharlotteDunois\Yasmin\Client {
    protected $dispatcher;
    protected $registry;
    protected $provider;
    
    protected $commandPrefix;
    
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
                return $this->commandPrefix;
            break;
            case 'owners':
                $owners = array();
                foreach($options['owners'] as $owner) {
                    $owners[] = $this->users->get($owner);
                }
                
                return $owners;
            break;
        }
        
        return parent::__get($name);
    }
    
    /**
	 * Sets the global command prefix. An empty string indicates that there is no default prefix, and only mentions will be used.
	 * Setting to `null` means that the default prefix from ClientOptions will be used instead.
     * Emits a commandPrefixChange event.
	 * @param string|null  $prefix
     * @return $this
	 */
    function setCommandPrefix($prefix) {
        $this->commandPrefix = $prefix;
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
            
            if($this->readyTimestamp) {
                $this->emit('debug', 'Provider set to '.$classname.' - initializing...');
			    $this->provider->init($this)->then($resolve, $reject);
                return;
            }
            
            $this->emit('debug', 'Provider set to '.$classname.' - will initialize once ready.');
            
            $this->once('ready', function () use ($resolve, $reject) {
                $this->emit('debug', 'Initializing provider...');
                $this->provider->init($this)->then($resolve, $reject);
            });
        }))->then(function () {
            $this->emit('debug', 'Provider finished initialization.');
        });
    }
    
    /**
     * @internal
     */
    function destroy(...$args) {
        return parent::destroy(...$args)->then(function () {
            if($this->provider) {
                return $this->provider->destroy();
            }
        });
    }
}
