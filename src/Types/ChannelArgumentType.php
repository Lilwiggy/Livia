<?php
/**
 * Livia
 * Copyright 2017-2018 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Types;

/**
 * @inheritDoc
 * @internal
 */
class ChannelArgumentType extends ArgumentType {
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, 'channel');
    }
    
    /**
     * @inheritDoc
     */
    function validate(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg = null) {
        $prg = \preg_match('/(?:<#)?(\d+)>?/', $value, $matches);
        if($prg === 1) {
            return $message->message->guild->channels->has($matches[1]);
        }
        
        $search = \mb_strtolower($value);
        
        $inexactChannels = $message->message->guild->channels->filter(function ($channel) use ($search) {
            return (\mb_stripos($channel->name, $search) !== false);
        });
        $inexactLength = $inexactChannels->count();
        
        if($inexactLength === 0) {
             return false;
        }
        if($inexactLength === 1) {
            return true;
        }
        
        $exactChannels = $message->message->guild->channels->filter(function ($channel) use ($search) {
            return ($channel->name === $search);
        });
        $exactLength = $exactChannels->count();
        
        if($exactLength === 1) {
            return true;
        }
        
        if($exactLength > 0) {
            $channels = $exactChannels;
        } else {
            $channels = $inexactChannels;
        }
        
        if($channels->count() >= 15) {
            return 'Multiple channels found. Please be more specific.';
        }
        
        $escapedChannels = \array_map(function ($channel) {
            return \CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown($channel->name);
        }, $channels->all());
        
        return \CharlotteDunois\Livia\Utils\DataHelpers::disambiguation($escapedChannels, 'channels', null).PHP_EOL;
    }
    
    /**
     * @inheritDoc
     */
    function parse(string $value, \CharlotteDunois\Livia\CommandMessage $message, \CharlotteDunois\Livia\Arguments\Argument $arg = null) {
        $prg = \preg_match('/(?:<#)?(\d+)>?/', $value, $matches);
        if($prg === 1) {
            return $message->message->guild->channels->get($matches[1]);
        }
        
        $search = \mb_strtolower($value);
        
        $inexactChannels = $message->message->guild->channels->filter(function ($channel) use ($search) {
            return (\mb_stripos($channel->name, $search) !== false);
        });
        $inexactLength = $inexactChannels->count();
        
        if($inexactLength === 0) {
             return null;
        }
        if($inexactLength === 1) {
            return $inexactChannels->first();
        }
        
        $exactChannels = $message->message->guild->channels->filter(function ($channel) use ($search) {
            return ($channel->name === $search);
        });
        $exactLength = $exactChannels->count();
        
        if($exactLength === 1) {
            return $exactChannels->first();
        }
        
        return null;
    }
}
