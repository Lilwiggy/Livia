<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        protected $timeformats = array('µs', 'ms');
        protected $lastResult;
        
        function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
            parent::__construct($client, array(
                'name' => 'evaluate',
                'aliases' => array('eval'),
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
                ),
                'guarded' => true
            ));
        }
        
        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern) {
            return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $args) {
                $code = $args['script'];
                if(\mb_substr($code, -1) !== ';') {
                    $code .= ';';
                }
                
                if(\mb_strpos($code, 'return') === false && \mb_strpos($code, 'echo') === false) {
                    $code = \explode(';', $code);
                    $code[(\count($code) - 2)] = PHP_EOL.'return '.\trim($code[(\count($code) - 2)]);
                    $code = \implode(';', $code);
                }
                
                \React\Promise\resolve()->then(function () use ($code, $message) {
                    $messages = array();
                    $time = null;
                    
                    $doCallback = function ($result) use ($code, $message, &$messages, &$time) {
                        $endtime = \microtime(true);
                        
                        $result = $this->invokeDump($result);
                        
                        $len = \mb_strlen($result);
                        $maxlen = 1850 - \mb_strlen($code);
                        
                        if($len > $maxlen) {
                            $result = \mb_substr($result, 0, $maxlen).PHP_EOL.'...';
                        }
                        
                        $sizeformat = \count($this->timeformats) - 1;
                        $format = 0;
                        
                        $exectime = $endtime - $time;
                        while($exectime < 1.0 && $format < $sizeformat) {
                            $exectime *= 1000;
                            $format++;
                        }
                        $exectime = \ceil($exectime);
                        
                        $messages[] = $message->say($message->message->author.'Executed after '.$exectime.$this->timeformats[$format].' (callback).'.PHP_EOL.PHP_EOL.'```php'.PHP_EOL.$result.PHP_EOL.'```'.($len > $maxlen ? PHP_EOL.'Original length: '.$len : ''));
                    };
                    
                    $endtime = null;
                    $time = \microtime(true);
                    
                    $result = eval($code);
                    
                    if(!($result instanceof \React\Promise\Promise)) {
                        $endtime = \microtime(true);
                        $result = \React\Promise\resolve($result);
                    }
                    
                    return $result->then(function ($result) use ($code, $message, &$messages, $endtime, $time) {
                        if($endtime === null) {
                            $endtime = \microtime(true);
                        }
                        
                        $this->lastResult = $result;
                        $result = $this->invokeDump($result);
                        
                        $len = \mb_strlen($result);
                        $maxlen = 1850 - \mb_strlen($code);
                        
                        if($len > $maxlen) {
                            $result = \mb_substr($result, 0, $maxlen).PHP_EOL.'...';
                        }
                        
                        $sizeformat = \count($this->timeformats) - 1;
                        $format = 0;
                        
                        $exectime = $endtime - $time;
                        while($exectime < 1.0 && $format < $sizeformat) {
                            $exectime *= 1000;
                            $format++;
                        }
                        $exectime = \ceil($exectime);
                        
                        $messages[] = $message->say($message->message->author.'Executed in '.$exectime.$this->timeformats[$format].'.'.PHP_EOL.PHP_EOL.'```php'.PHP_EOL.$result.PHP_EOL.'```'.($len > $maxlen ? PHP_EOL.'Original length: '.$len : ''));
                        return $messages;
                    });
                })->then(function ($pr) {
                    return $pr;
                }, function ($e) use ($code, $message, &$messages) {
                    while(@\ob_end_clean());
                    
                    $e = (string) $e;
                    $len = \mb_strlen($e);
                    $maxlen = 1900 - \mb_strlen($code);
                    
                    if($len > $maxlen) {
                        $e = \mb_substr($e, 0, $maxlen).PHP_EOL.'...';
                    }
                    
                    $messages[] = $message->say($message->message->author.PHP_EOL.'```php'.PHP_EOL.$code.PHP_EOL.'```'.PHP_EOL.'Error: ```'.PHP_EOL.$e.PHP_EOL.'```');
                    return $messages;
                })->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
            }));
        }
        
        function invokeDump($result) {
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
            
            return $result;
        }
    });
};
