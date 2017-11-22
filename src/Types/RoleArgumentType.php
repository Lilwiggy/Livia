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
class RoleArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, 'role');
    }
    
    /**
	 * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $prg = \preg_match(\CharlotteDunois\Yasmin\Models\MessageMentions::PATTERN_ROLES, $value, $matches);
        if($prg === 1) {
            return $message->message->guild->roles->has($matches[1]);
        }
        
        $search = \strtolower($value);
        
        $inexactRoles = $message->message->guild->roles->filter(function ($role) use ($search) {
            return (stripos($role->name, $search) !== false);
        });
        $inexactLength = $inexactRoles->count();
        
        if($inexactLength === 0) {
             return false;
        }
        if($inexactLength === 1) {
            return true;
        }
        
        $exactRoles = $message->message->guild->roles->filter(function ($role) use ($search) {
            return ($role->name === $search);
        });
        $exactLength = $exactRoles->count();
        
        if($exactLength === 1) {
            return true;
        }
        
        if($exactLength > 0) {
            $roles = $exactRoles;
        } else {
            $roles = $inexactRoles;
        }
        
        if($roles->count() >= 15) {
            return 'Multiple roles found. Please be more specific.';
        }
        
        $escapedRoles = \array_map(function ($role) {
            return \CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown($role->name);
        }, $roles->all());
        
        return \CharlotteDunois\Livia\Utils\DataHelpers::disambiguation($escapedRoles, 'roles', null).PHP_EOL;
    }
    
    /**
	 * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $prg = \preg_match(\CharlotteDunois\Yasmin\Models\MessageMentions::PATTERN_CHANNELS, $value, $matches);
        if($prg === 1) {
            return $message->message->guild->roles->get($matches[1]);
        }
        
        $search = \strtolower($value);
        
        $inexactRoles = $message->message->guild->roles->filter(function ($role) use ($search) {
            return (stripos($role->name, $search) !== false);
        });
        $inexactLength = $inexactRoles->count();
        
        if($inexactLength === 0) {
             return null;
        }
        if($inexactLength === 1) {
            return $inexactRoles->first();
        }
        
        $exactRoles = $message->message->guild->roles->filter(function ($role) use ($search) {
            return ($role->name === $search);
        });
        $exactLength = $exactRoles->count();
        
        if($exactLength === 1) {
            return $exactRoles->first();
        }
        
        return null;
    }
}
