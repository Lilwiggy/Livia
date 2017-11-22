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
class Evaluate extends \CharlotteDunois\Livia\Commands\Command {
    protected $lastResult;
    
    function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
        parent::__construct($client, array(
            'name' => 'eval',
            'aliases' => array(),
            'group' => 'utils',
            'description' => 'Executes PHP code.',
            'guildOnly' => false,
            'ownerOnly' => true,
            'argsSingleQuotes' => false,
            'args' => array(
                array(
                    'key' => 'script',
                    'prompt' => 'What is the fancy code you wanna run?',
                    'type' => 'string'
                )
            )
        ));
    }
    
    function run(\CharlotteDunois\Livia\CommandMessage $message, array $args, bool $fromPattern) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $args) {
            $code = $args['script'];
            if(\substr($code, -1) !== ';') {
                $code .= ';';
            }
            
            if(\strpos($code, 'return') === false && \strpos($code, 'echo') === false) {
                $code = \explode(';', $code);
                $code[(\count($code) - 2)] = PHP_EOL.'return '.\trim($code[(\count($code) - 2)]);
                $code = \implode(';', $code);
            }
            
            \React\Promise\resolve()->then(function () use ($code, $message) {
                $endtime = null;
                $time = microtime(true);
                
                $result = eval($code);
                
                if(!($result instanceof \React\Promise\Promise)) {
                    $endtime = microtime(true);
                    $result = \React\Promise\resolve($result);
                }
                
                return $result->then(function ($result) use ($code, $message, $endtime, $time) {
                    if($endtime === null) {
                        $endtime = microtime(true);
                    }
                    
                    \ob_start('mb_output_handler');
                    
                    $old = \ini_get('xdebug.var_display_max_depth');
                    \ini_set('xdebug.var_display_max_depth', 1);
                    
                    \var_dump($result);
                    \ini_set('xdebug.var_display_max_depth', $old);
                    $result = @\ob_get_clean();
                    
                    $result = \explode("\n", \str_replace("\r", "", $result));
                    \array_shift($result);
                    $result = \implode(PHP_EOL, $result);
                    
                    while(@\ob_end_clean());
                    
                    $this->lastResult = $result;
                    
                    $len = \strlen($result);
                    $maxlen = 1850 - \strlen($code);
                    
                    if($len > $maxlen) {
                        $result = \substr($result, 0, $maxlen).PHP_EOL.'...';
                    }
                    
                    $formats = array('s', 'ms', 'µs', 'ns');
                    $format = 0;
                    
                    $exectime = $endtime - $time;
                    while($exectime > 1.0 && $format < 3) {
                        $exectime /= 1000;
                        $format++;
                    }
                    $exectime = \ceil($exectime);
                    
                    return $message->message->channel->send($message->message->author.'Executed in '.$exectime.$formats[$format].'.'.PHP_EOL.'Result:'.PHP_EOL.'```php'.PHP_EOL.$result.PHP_EOL.'```'.($len > $maxlen ? PHP_EOL.'Original length: '.$len : ''));
                });
            })->then(function ($pr) {
                return $pr;
            }, function ($e) use ($code, $message) {
                while(@\ob_end_clean());
                
                $e = (string) $e;
                $len = \strlen($e);
                $maxlen = 1900 - \strlen($code);
                
                if($len > $maxlen) {
                    $e = \substr($e, 0, $maxlen).PHP_EOL.'...';
                }
                
                return $message->message->channel->send($message->message->author.PHP_EOL.'```php'.PHP_EOL.$code.PHP_EOL.'```'.PHP_EOL.'Error: ```'.PHP_EOL.$e.PHP_EOL.'```');
            })->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
}
