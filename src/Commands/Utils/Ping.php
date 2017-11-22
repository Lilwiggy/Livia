<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Commands\Utils;

/**
 * @internal
 */
class Ping extends \CharlotteDunois\Livia\Commands\Command {
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, array(
            'name' => 'ping',
            'aliases' => array(),
            'group' => 'utils',
            'description' => 'Sends a ping and measures the latency between command message and ping message. It will also display websocket ping.',
            'guildOnly' => false,
            'throttling' => array(
                'usages' => 5,
                'duration' => 10
            )
        ));
    }
    
    function run(\CharlotteDunois\Livia\CommandMessage $message, array $args, bool $fromPattern) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message) {
            $message->message->channel->send('Pinging...')->then(function ($msg) use ($message, $resolve, $reject) {
                $time = \CharlotteDunois\Yasmin\Utils\Snowflake::deconstruct($msg->id)->timestamp - \CharlotteDunois\Yasmin\Utils\Snowflake::deconstruct($message->id)->timestamp;
                
                $ping = $this->client->getPing();
                if(!\is_int($ping)) {
                    $ping = 0;
                }
                
                $msg->edit($message->author.' Pong! The message round-trip took '.\ceil(($time * 1000)).'ms. The WS heartbeat is '.$ping.'ms.')->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
            }, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
}
