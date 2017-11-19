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
class BooleanArgumentType extends ArgumentType {
    protected $truthy = array('true', 't', 'yes', 'y', 'on', 'enable', 'enabled', '1', '+');
    protected $falsey = array('false', 'f', 'no', 'n', 'off', 'disable', 'disabled', '0', '-');
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\CommandClient $client) {
        parent::__construct($client, 'boolean');
    }
    
    /**
	 * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $value = \strtolower($value);
        return (\in_array($value, $this->truthy) || \in_array($value, $tthis->falsey));
    }
    
    /**
	 * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $value = \strtolower($value);
        if(\in_array($value, $this->truthy)) {
            return true;
        }
        
        if(\in_array($value, $tthis->falsey)) {
            return false;
        }
        
        throw new \RangeException('Unknown boolean value.');
    }
}
