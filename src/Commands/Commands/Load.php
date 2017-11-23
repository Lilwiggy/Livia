<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Commands\Commands;

/**
 * @internal
 */
class Load extends \CharlotteDunois\Livia\Commands\Command {
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, array(
            'name' => 'load',
            'aliases' => array('load-command'),
            'group' => 'commands',
            'description' => 'Loads a new command.',
            'details' => 'The argument must be full name of the command in the format of `group:name`. Only the bot owner may use this command.',
            'examples' => array('enable utils'),
            'guildOnly' => false,
            'ownerOnly' => true,
            'args' => array(
                array(
                    'key' => 'command',
                    'prompt' => 'Which command would you like to load?',
                    'validate' => function ($value) {
                        $value = \explode(':', $value);
                        if(\count($value) !== 2) {
                            return false;
                        }
                        
                        if(\count($this->client->registry->findCommands($value[1])) > 0) {
                            return 'That command is already registered.';
                        }
                        
                        try {
                            $this->client->registry->resolveCommandPath($value[0], $value[1]);
                            return true;
                        } catch(\Exception $e) {
                            return false;
                        }
                    },
                    'parse' => function ($value) {
                        return $value;
                    }
                )
            ),
            'guarded' => true
        ));
    }
    
    function run(\CharlotteDunois\Livia\CommandMessage $message, array $args, bool $fromPattern) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $args) {
            $value = \explode(':', $args['command']);
            $path = $this->client->registry->resolveCommandPath($value[0], $value[1]);
            include($path);
            
            $this->client->registry->registerCommand($args['command']);
            $resolve($message->reply('Loaded the command `'.$args['command'].'`.'));
        }));
    }
}
