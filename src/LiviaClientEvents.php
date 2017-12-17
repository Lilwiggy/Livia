<?php
/**
 * Yasmin
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia;

/**
 * Documents all LiviaClient events (exlucing events from Yasmin).
 */
interface LiviaClientEvents {
    /**
     * Emitted when something out of expectation occurres. A warning for you.
     */
    function warn(string $message);
    
    /**
     * Emitted when a command is prevented from running.
     */
    function commandBlocked(\CharlotteDunois\Livia\CommandMessage $message, string $reason);
    
    /**
     * Emitted when a command produces an error while running.
     */
    function commandError(\CharlotteDunois\Livia\Commands\Command $command, \Throwable $error, \CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern);
    
    /**
     * Emitted when running a command.
     */
    function commandRun(\CharlotteDunois\Livia\Commands\Command $command, \React\Promise\PromiseInterface $promise, \CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern);
    
    /**
     * Emitted when an user tries to use an unknown command.
     */
    function unknownCommand(\CharlotteDunois\Livia\CommandMessage $message);
    
    /**
     * Emitted when a command is registered.
     */
    function commandRegister(\CharlotteDunois\Livia\Commands\Command $command, \CharlotteDunois\Livia\CommandRegistry $registry);
    
    /**
     * Emitted when a command is re-registered.
     */
    function commandReregister(\CharlotteDunois\Livia\Commands\Command $command, \CharlotteDunois\Livia\Commands\Command $oldCommand, \CharlotteDunois\Livia\CommandRegistry $registry);
    
    /**
     * Emitted when a command is unregistered.
     */
    function commandUnregister(\CharlotteDunois\Livia\Commands\Command $command, \CharlotteDunois\Livia\CommandRegistry $registry);
    
    /**
     * Emitted when a group is registered.
     */
    function groupRegister(\CharlotteDunois\Livia\Commands\CommandGroup $group, \CharlotteDunois\Livia\CommandRegistry $registry);
    
    /**
     * Emitted when an argument type is registered.
     */
    function typeRegister(\CharlotteDunois\Livia\Types\ArgumentType $type, \CharlotteDunois\Livia\CommandRegistry $registry);
    
    /**
     * Emitted whenever a guild's command prefix is changed. Guild will be null if the prefix is global. Prefix will be null if it is changed to default.
     * @param \CharlotteDunois\Yasmin\Models\Guild|null  $guild
     * @param string|null                                $newPrefix
     */
    function commandPrefixChange($guild, $newPrefix);
    
    /**
     * Emitted whenever a command is enabled/disabled in a guild. Guild will be null if status is global.
     * @param \CharlotteDunois\Yasmin\Models\Guild|null  $guild
     * @param \CharlotteDunois\Livia\Commands\Command    $command
     * @param bool                                       $enabled
     */
    function commandStatusChange($guild, \CharlotteDunois\Livia\Commands\Command $command, bool $enabled);
    
    /**
     * Emitted whenever a group is enabled/disabled in a guild. Guild will be null if status is global.
     * @param \CharlotteDunois\Yasmin\Models\Guild|null     $guild
     * @param \CharlotteDunois\Livia\Commands\CommandGroup  $group
     * @param bool                                          $enabled
     */
    function groupStatusChange($guild, \CharlotteDunois\Livia\Commands\CommandGroup $group, bool $enabled);
}
