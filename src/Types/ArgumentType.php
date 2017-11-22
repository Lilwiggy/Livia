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
 * An argument type that can be used for argument collecting.
 *
 * @property \CharlotteDunois\Livia\LiviaClient        $client    The client which initiated the instance.
 * @property string                                    $id        The argument type ID.
 */
abstract class ArgumentType {
    protected $client;
    protected $id;
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client, string $id) {
        $this->client = $client;
        $this->id = $id;
    }
    
    /**
     * @internal
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception('Unknown property '.\get_class($this).'::'.$name);
    }
    
    /**
	 * Validates a value against the type.
	 * @param string                                     $value  Value to validate.
	 * @param \CharlotteDunois\Livia\CommandMessage      $msg    Message the value was obtained from.
	 * @param \CharlotteDUnois\Livia\Arguments\Argument  $arg    Argument the value obtained from.
     * @return bool|string|\React\Promise\Promise
     */
    abstract function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg);
    
    /**
	 * Parses a value into an usable value.
	 * @param string                                     $value  Value to parse.
	 * @param \CharlotteDunois\Livia\CommandMessage      $msg    Message the value was obtained from.
	 * @param \CharlotteDUnois\Livia\Arguments\Argument  $arg    Argument the value obtained from.
     * @return mixed|null
     * @throws \RangeException
     */
    abstract function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg);
    
    /**
	 * Checks whether a value is considered to be empty. This determines whether the default value for an argument should be used and changes the response to the user under certain circumstances.
	 * @param mixed                                      $value  Value to check.
	 * @param \CharlotteDunois\Livia\CommandMessage      $msg    Message the value was obtained from.
	 * @param \CharlotteDUnois\Livia\Arguments\Argument  $arg    Argument the value obtained from.
     * @return bool
     */
    function isEmpty($value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        return empty($value);
    }
}
