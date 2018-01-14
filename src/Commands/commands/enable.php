<?php
/**
 * Livia
 * Copyright 2017-2018 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
            parent::__construct($client, array(
                'name' => 'enable',
                'aliases' => array('enable-command'),
                'group' => 'commands',
                'description' => 'Enables a command or command group.',
                'details' => 'The argument must be the name/ID (partial or whole) of a command or command group. Only administrators may use this command.',
                'examples' => array('enable utils'),
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 2,
                    'duration' => 3
                ),
                'userPermissions' => array('ADMINISTRATOR'),
                'args' => array(
                    array(
                        'key' => 'commandOrGroup',
                        'label' => 'command/group',
                        'prompt' => 'Which command or group would you like to enable?',
                        'type' => 'command-or-group'
                    )
                ),
                'guarded' => true
            ));
        }
        
        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern) {
            return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $args) {
                $type = ($args['commandOrGroup'] instanceof \CharlotteDunois\Livia\Commands\CommandGroup ? 'group' : 'command');
                
                if($args['commandOrGroup']->isEnabledIn($message->message->guild)) {
                    return $resolve($message->reply('The '.$type.' `'.$args['commandOrGroup']->name.'` is already enabled.'));
                }
                
                $args['commandOrGroup']->setEnabledIn($message->message->guild, true);
                $resolve($message->reply('Enabled the '.$type.' `'.$args['commandOrGroup']->name.'`.'));
            }));
        }
    });
};
