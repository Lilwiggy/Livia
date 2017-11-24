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
class UserArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, 'user');
    }
    
    /**
	 * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $prg = \preg_match(\CharlotteDunois\Yasmin\Models\MessageMentions::PATTERN_USERS, $value, $matches);
        if($prg === 1) {
            return $message->client->fetchUser($matches[1])->then(function () {
                return true;
            }, function () {
                return false;
            });
        }
        
        $search = \strtolower($value);
        
        $inexactUsers = $this->client->users->filter(function ($user) use ($search) {
            return (stripos($user->tag, $search) !== false);
        });
        $inexactLength = $inexactUsers->count();
        
        if($inexactLength === 0) {
             return false;
        }
        if($inexactLength === 1) {
            return true;
        }
        
        $exactUsers = $this->client->users->filter(function ($user) use ($search) {
            return ($user->tag === $search);
        });
        $exactLength = $exactUsers->count();
        
        if($exactLength === 1) {
            return true;
        }
        
        if($exactLength > 0) {
            $users = $exactUsers;
        } else {
            $users = $inexactUsers;
        }
        
        if($users->count() >= 15) {
            return 'Multiple users found. Please be more specific.';
        }
        
        $escapedUsers = \array_map(function ($user) {
            return \CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown($user->tag);
        }, $users->all());
        
        return \CharlotteDunois\Livia\Utils\DataHelpers::disambiguation($escapedUsers, 'users', null).PHP_EOL;
    }
    
    /**
	 * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $prg = \preg_match(\CharlotteDunois\Yasmin\Models\MessageMentions::PATTERN_USERS, $value, $matches);
        if($prg === 1) {
            return $this->client->users->get($matches[1]);
        }
        
        $search = \strtolower($value);
        
        $inexactUsers = $this->client->users->filter(function ($user) use ($search) {
            return (stripos($user->tag, $search) !== false);
        });
        $inexactLength = $inexactUsers->count();
        
        if($inexactLength === 0) {
             return null;
        }
        if($inexactLength === 1) {
            return $inexactUsers->first();
        }
        
        $exactUsers = $this->client->users->filter(function ($user) use ($search) {
            return ($user->tag === $search);
        });
        $exactLength = $exactUsers->count();
        
        if($exactLength === 1) {
            return $exactUsers->first();
        }
        
        return null;
    }
}
