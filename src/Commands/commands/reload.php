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
                'name' => 'reload',
                'aliases' => array('reload-command'),
                'group' => 'commands',
                'description' => 'Reloads a command or command group.',
                'details' => 'The argument must be the name/ID (partial or whole) of a command or command group. Providing a command group will reload all of the commands in that group. Only the bot owner may use this command.',
                'examples' => array('enable utils'),
                'guildOnly' => false,
                'ownerOnly' => true,
                'args' => array(
                    array(
                        'key' => 'commandOrGroup',
                        'label' => 'command/group',
                        'prompt' => 'Which command or command group would you like to reload?',
                        'type' => 'command-or-group'
                    )
                ),
                'guarded' => true
            ));
        }
        
        function run(\CharlotteDunois\Livia\CommandMessage $message, array $args, bool $fromPattern) {
            return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $args) {
                if($args['commandOrGroup'] instanceof \CharlotteDunois\Livia\Commands\CommandGroup) {
                    $args['commandOrGroup']->reload();
                    $resolve($message->reply('Reloaded the group `'.$args['commandOrGroup']->name.'`.'));
                } else {
                    $args['commandOrGroup']->reload();
                    $resolve($message->reply('Reloaded the command `'.$args['commandOrGroup']->name.'`.'));
                }
            }));
        }
    });
};
