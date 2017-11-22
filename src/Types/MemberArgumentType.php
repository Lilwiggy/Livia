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
class MemberArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, 'member');
    }
    
    /**
	 * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $prg = \preg_match(\CharlotteDunois\Yasmin\Models\MessageMentions::PATTERN_USERS, $value, $matches);
        if($prg === 1) {
            return $message->message->guild->fetchMember($matches[1])->then(function () {
                return true;
            }, function () {
                return false;
            });
        }
        
        $search = \strtolower($value);
        
        $inexactMembers = $message->message->guild->members->filter(function ($member) use ($search) {
            return (stripos($member->tag, $search) !== false || stripos($member->displayName, $search) !== false);
        });
        $inexactLength = $inexactMembers->count();
        
        if($inexactLength === 0) {
             return false;
        }
        if($inexactLength === 1) {
            return true;
        }
        
        $exactMembers = $message->message->guild->members->filter(function ($member) use ($search) {
            return ($member->tag === $search || $member->displayName === $search);
        });
        $exactLength = $exactMembers->count();
        
        if($exactLength === 1) {
            return true;
        }
        
        if($exactLength > 0) {
            $members = $exactMembers;
        } else {
            $members = $inexactMembers;
        }
        
        if($members->count() >= 15) {
            return 'Multiple members found. Please be more specific.';
        }
        
        $escapedMembers = \array_map(function ($member) {
            return \CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown($member->tag);
        }, $members->all());
        
        return \CharlotteDunois\Livia\Utils\DataHelpers::disambiguation($escapedMembers, 'members', null).PHP_EOL;
    }
    
    /**
	 * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg) {
        $prg = \preg_match(\CharlotteDunois\Yasmin\Models\MessageMentions::PATTERN_USERS, $value, $matches);
        if($prg === 1) {
            return $message->message->guild->members->get($matches[1]);
        }
        
        $search = \strtolower($value);
        
        $inexactMembers = $message->message->guild->members->filter(function ($member) use ($search) {
            return (stripos($member->tag, $search) !== false || stripos($member->displayName, $search) !== false);
        });
        $inexactLength = $inexactMembers->count();
        
        if($inexactLength === 0) {
             return null;
        }
        if($inexactLength === 1) {
            return $inexactMembers->first();
        }
        
        $exactMembers = $message->message->guild->members->filter(function ($member) use ($search) {
            return ($member->tag === $search || $member->displayName === $search);
        });
        $exactLength = $exactMembers->count();
        
        if($exactLength === 1) {
            return $exactMembers->first();
        }
        
        return null;
    }
}
