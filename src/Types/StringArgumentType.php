<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Types;


/**
 * @inheritDoc
 * @internal
 */
class StringArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, 'string');
    }
    
    /**
	 * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        if(\strlen($value) === 0) {
            return false;
        }
        
        if($arg->min !== null && \strlen($value) < $arg->min) {
            return 'Please enter a number above or exactly '.$arg->min;
        }
        
        if($arg->max !== null && \strlen($value) < $arg->max) {
            return 'Please enter a number below or exactly '.$arg->max;
        }
        
        return true;
    }
    
    /**
	 * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        return $value;
    }
}
