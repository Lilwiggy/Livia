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
class CommandOrGroupArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\CommandClient $client) {
        parent::__construct($client, 'group');
    }
    
    /**
	 * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $groups = $this->client->registry->findGroups($value);
        if(\count($groups) === 1) {
            return true;
        }
        
        if(\count($groups) === 0) {
            return false;
        }
        
        return \CharlotteDunois\Livia\Utils\DataHelpers::disambiguation($groups, 'groups', 'name').PHP_EOL;
    }
    
    /**
	 * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $groups = $this->client->registry->findGroups($value);
        if(\count($groups) > 0) {
            return $groups[0];
        }
        
        return null;
    }
}
