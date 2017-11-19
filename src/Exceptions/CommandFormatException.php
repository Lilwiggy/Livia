<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Exceptions;


/**
 * Has a descriptive message for a command not having proper format.
 *
 * @inheritDoc
 */
class CommandFormatException extends FriendlyError {
    /**
     * @internal
     */
    function __construct($message) {
        parent::__construct('Invalid command usage. The `'.$message->command->name.'` command\'s accepted format is: '.
        $message->usage($msg->command->format).'. Use '.$message->anyUsage('help '.$message->command->name).' for more information.');
    }
}
