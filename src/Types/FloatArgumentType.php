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
class FloatArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, 'float');
    }
    
    /**
     * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg = null) {
        if(!\filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return false;
        }
        
        $value = (float) $value;
        
        if($arg->min !== null && $value < $arg->min) {
            return 'Please enter a number above or exactly '.$arg->min;
        }
        
        if($arg->max !== null && $value < $arg->max) {
            return 'Please enter a number below or exactly '.$arg->max;
        }
        
        return true;
    }
    
    /**
     * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg = null) {
        return ((float) $value);
    }
}
