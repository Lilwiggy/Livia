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
 *
 * @method  commandBlocked(\CharlotteDunois\Livia\CommandMessage $message, string reason)                                                                                                            Emitted when a command is prevented from running.
 * @method  commandError(\CharlotteDunois\Livia\Commands\Command $command, \Throwable $error, \CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)                        Emitted when a command produces an error while running.
 * @method  commandRun(\CharlotteDunois\Livia\Commands\Command $command, \React\Promise\PromiseInterface $promise, \CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)   Emitted when running a command.
 * @method  unknownCommand(\CharlotteDunois\Livia\CommandMessage $message)                                                                                                                           Emitted when an user tries to use an unknown command.
 *
 * @method  commandRegister(\CharlotteDunois\Livia\Commands\Command $command, \CharlotteDunois\Livia\CommandRegistry $registry)                                                                      Emitted when a command is registered.
 * @method  commandReregister(\CharlotteDunois\Livia\Commands\Command $command, \CharlotteDunois\Livia\Commands\Command $oldCommand, \CharlotteDunois\Livia\CommandRegistry $registry)               Emitted when a command is re-registered.
 * @method  commandUnregister(\CharlotteDunois\Livia\Commands\Command $command, \CharlotteDunois\Livia\CommandRegistry $registry)                                                                    Emitted when a command is unregistered.
 * @method  groupRegister(\CharlotteDunois\Livia\Commands\CommandGroup $group, \CharlotteDunois\Livia\CommandRegistry $registry)                                                                     Emitted when a group is registered.
 * @method  typeRegister(\CharlotteDunois\Livia\Types\ArgumentType $type, \CharlotteDunois\Livia\CommandRegistry $registry)                                                                          Emitted when an argument type is registered.
 *
 * @method  commandPrefixChange(\CharlotteDunois\Yasmin\Models\Guild|null $guild, string|null $newPrefix)                                                                                            Emitted whenever a guild's command prefix is changed. Guild will be null if the prefix is global. Prefix will be null if it is changed to default.
 * @method  commandStatusChange(\CharlotteDunois\Yasmin\Models\Guild|null $guild, \CharlotteDunois\Livia\Commands\Command $command, bool $enabled)                                                   Emitted whenever a command is enabled/disabled in a guild. Guild will be null if status is global.
 * @method  groupStatusChange(\CharlotteDunois\Yasmin\Models\Guild|null $guild, \CharlotteDunois\Livia\Commands\CommandGroup $group, bool $enabled)                                                  Emitted whenever a group is enabled/disabled in a guild. Guild will be null if status is global.
 *
 * @method  warn(string $message)                                                                                                                                                                    Emitted when something out of expectation occurres. A warning for you.
 */
interface LiviaClientEvents {
    
}
