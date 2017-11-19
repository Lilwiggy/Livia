<?php
/**
 * Livia
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

namespace CharlotteDunois\Livia\Arguments;


/**
 * A fancy argument.
 *
 * @property \CharlotteDunois\Livia\CommandClient        $client    The client which initiated the instance.
 * @property string                                      $key       Key for the argument.
 * @property string                                      $label     Label for the argument.
 * @property string                                      $prompt    Question prompt for the argument.
 * @property \CharlotteDunois\Livia\Types\ArgumentType   $type      Type of the argument.
 * @property int|float|null                              $max       If type is integer or float, this is the maximum value of the number. If type is string, this is the maximum length of the string.
 * @property int|float|null                              $min       If type is integer or float, this is the minimum value of the number. If type is string, this is the minimum length of the string.
 * @property mixed|null                                  $default   The default value for the argument.
 * @property bool                                        $infinite  Whether the argument accepts an infinite number of values.
 * @property callable|null                               $validate  Validator function for validating a value for the argument.
 * @property callable|null                               $parse     Parser function to parse a value for the argument.
 * @property int                                         $wait      How long to wait for input (in seconds).
 */
class Argument {
    protected $client;
    
    protected $key;
    protected $label;
    protected $prompt;
    protected $type;
    protected $max;
    protected $min;
    protected $default;
    protected $infinite;
    protected $validate;
    protected $parse;
    protected $wait;
    
    /**
     * Constructs a new Argument. Info is an array as following:
     *
     *  array(                                                                                                                                        <br />
     *      'key' => string, (Key for the argument)                                                                                                   <br />
     *      'label' => string, (Label for the argument, defaults to key)                                                                              <br />
     *      'prompt' => string, (First prompt for the argument when it wasn't specified)                                                              <br />
     *      'type' => string, (Type of the argument, must be the ID of one of the registered argument types)                                          <br />
     *      'max' => int|float, (If type is integer or float this is the maximum value, if type is string this is the maximum length, optional)       <br />
     *      'min' => int|float, (If type is integer or float this is the minimum value, if type is string this is the minimum length, optional)       <br />
     *      'default' => mixed, (Default value for the argumen, must not be null, optional)                                                           <br />
     *      'infinite' => bool, (Infinite argument collecting, defaults to false)                                                                     <br />
     *      'validate' => callable, (Validator function for the argument, optional)                                                                   <br />
     *      'parse' => callable, (Parser function for the argument, optional)                                                                         <br />
     *      'wait' => int (How long to wait for input (in seconds)                                                                                    <br />
     *  )
     *
     * @param \CharlotteDunois\Livia\CommandClient  $client
     * @param array                                 $info
     * @throws \InvalidArgumentException
     */
    function __construct(\CharlotteDunois\Livia\CommandClient $client, array $info) {
        $this->client = $client;
        
        if(empty($info['key'])) {
            throw new \InvalidArgumentException('Key can not be empty');
        }
        if(empty($info['prompt'])) {
            throw new \InvalidArgumentException('Prompt can not be empty');
        }
        if(empty($info['type'])) {
            throw new \InvalidArgumentException('Argument type can not be empty');
        }
        if(!$this->client->registry->types->has($info['type'])) {
            throw new \InvalidArgumentException('Argument type "'.$info['type'].'" is not registered');
        }
        if(isset($info['max']) && !\is_int($info['max']) && !\is_float($info['max'])) {
            throw new \InvalidArgumentException('Max is not an float or an integer');
        }
        if(isset($info['min']) && !\is_int($info['min']) && !\is_float($info['min'])) {
            throw new \InvalidArgumentException('Min is not an float or an integer');
        }
        
        $this->key = (string) $info['key'];
        $this->label = (!empty($info['label']) ? $info['label'] : $info['key']);
        $this->prompt = (string) $info['prompt'];
        $this->type = $this->client->registry->types->get($info['type']);
        $this->max = $info['max'] ?? null;
        $this->min = $info['min'] ?? null;
        $this->default = $info['default'] ?? null;
        $this->infinite = (!empty($info['infinite']));
        $this->validate = (!empty($info['validate']) && \is_callable($info['validate']) ? $info['validate'] : null);
        $this->parse = (!empty($info['parse']) && \is_callable($info['parse']) ? $info['parse'] : null);
        $this->wait = (int) ($info['wait'] ?? 30);
    }
    
    /**
     * @internal
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception('Unknown property \CharlotteDunois\Livia\Arguments\Argument::'.$name);
    }
    
    /**
     * Prompts the user and obtains the value for the argument. Resolves with an array of ('value' => mixed, 'cancelled' => string|null, 'prompts' => Message[], 'answers' => Message[]). Cancelled can be one of user, time and promptLimit.
     * @param \CharlotteDunois\Livia\CommandMessage  $message      Message that triggered the command.
     * @param string|string[]                        $value        Pre-provided value(s).
     * @param int|double                             $promptLimit  Maximum number of times to prompt for the argument.
     * @param bool|null                              $valid        Whether the last retrieved value was valid.
     * @return \React\Promise\Promise
     */
    function obtain(\CharlotteDunois\Livia\CommandMessage $message, $value, $promptLimit = \INF, array $prompts = array(), array $answers = array(), bool $valid = null) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $value, $promptLimit, $prompts, $answers, $valid) {
            if(!$value && $this->default) {
                return $resolve(array(
                    'value' => $this->default,
                    'cancelled' => null,
                    'prompts' => array(),
                    'answers' => array()
                ));
            }
            
            if($this->infinite) {
                $this->obtainInfinite($message, (\is_array($value) ? $value : array($value)), $promptLimit)->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
                return;
            }
            
            if(\count($prompts) > $promptLimit) {
                return $resolve(array(
                    'value' => null,
                    'cancelled' => 'promptLimit',
                    'prompts' => $prompts,
                    'answers' => $answers
                ));
            }
            
            // Prompt the user for a new value
            $message->reply(($value === null ? $this->prompt : ($valid ? $valid : 'You provided an invalid '.$this->label.'.')).PHP_EOL.
                            'Please try again. Respond with `cancel` to cancel the command. The command will automatically be cancelled in  '.$this->wait.' seconds.')
                        ->then(function ($msg) use ($message, $valid, $promptLimit, $prompts, $answers, $resolve, $reject) {
                            $prompts[] = $msg;
                            
                            // Get the user's response
                            $message->channel->collectMessages(function ($msg) use ($message) {
                                return ($msg->author->id === $message->author->id);
                            }, array(
                                'max' => 1,
                                'time' => $this->wait
                            ))->then(function ($messages) use ($message, $promptLimit, $prompts, $answers, $resolve) {
                                if($messages->count() === 0) {
                                    return $resolve(array(
                                        'value' => null,
                                        'cancelled' => 'time',
                                        'prompts' => $prompts,
                                        'answers' => $answers
                                    ));
                                }
                                
                                $msg = $messages->first();
                                $answers[] = $msg;
                                
                                $value = $msg->content;
                                
                                if(\strtolower($value) === 'cancel') {
                                    return $resolve(array(
                                        'value' => null,
                                        'cancelled' => 'user',
                                        'prompts' => $prompts,
                                        'answers' => $answers
                                    ));
                                }
                                
                                $validate = ($this->validate ? array($this, 'validate') : array($this->type, 'validate'))($value, $message, $this->type);
                                if(!($validate instanceof \React\Promise\PromiseInterface)) {
                                    $validate = \React\Promise\resolve($validate);
                                }
                                
                                return $validate->then(function ($valid) use ($message, $value, $promptLimit, $prompts, $answers) {
                                    if($valid === false) {
                                        return $this->obtain($message, $value, $promptLimit, $prompts, $answers, $valid);
                                    }
                                    
                                    return ($this->parse ? array($this, 'parse') : array($this->type, 'parse'))($value, $message, $this->type);
                                });
                            }, function () use ($resolve) {
                                return $resolve(array(
                                    'value' => null,
                                    'cancelled' => 'time',
                                    'prompts' => array(),
                                    'answers' => array()
                                ));
                            })->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
                        });
        }));
    }
    
    /**
     * Prompts the user infinitely and obtains the values for the argument. Resolves with an array of ('values' => mixed, 'cancelled' => string|null, 'prompts' => Message[], 'answers' => Message[]). Cancelled can be one of user, time and promptLimit.
     * @param \CharlotteDunois\Livia\CommandMessage  $message      Message that triggered the command.
     * @param string[]                               $values       Pre-provided values.
     * @param int|double                             $promptLimit  Maximum number of times to prompt for the argument.
     * @param int                                    $currentVal   Current value getting obtained.
     * @return \React\Promise\Promise
     */
    function obtainInfinite(\CharlotteDunois\Livia\CommandMessage $message, array $values = array(), $promptLimit = \INF, array &$prompts = array(), array &$answers = array(), int $currentVal = 0) {
        return (new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $values, $promptLimit, $prompts, $answers, $currentVal) {
            $value = null;
            if(!empty($values)) {
                $value = $values[(\count($values) - 1)];
            }
            
            $this->infiniteObtain($message, $value, $values, $promptLimit, $prompts, $answers)->then(function ($value) use ($message, $values, $promptLimit, $prompts, $answers, $currentVal, $resolve) {
                if(\is_array($value)) {
                    return $resolve($value);
                }
                
                $values[] = $value;
                $currentVal++;
                
                return $this->obtainInfinite($message, $values, $promptLimit, $prompts, $answers, $currentVal);
            })->then($resolve, $reject)->done(null, array($this->client, 'handlePromiseRejection'));
        }));
    }
    
    protected function infiniteObtain(\CharlotteDunois\Livia\CommandMessage $message, $value, array &$values, $promptLimit, array &$prompts, array &$answers, bool $valid = null) {
        if($value === null) {
            $reply = $message->reply($this->prompt.PHP_EOL.
                'Respond with `cancel` to cancel the command, or `finish` to finish entry up to this point.'.PHP_EOL.
                'The command will automatically be cancelled in '.$this->wait.' seconds.');
        } elseif($valid === false) {
            $escaped = \str_replace('@', "@\u{200B}", \CharlotteDunois\Yasmin\Utils\DataHelpers::escapeMarkdown($value));
            
            $reply = $message->reply('You provided an invalid '.$this->label.','.PHP_EOL.
                '"'.(\strlen($escaped) < 1850 ? $escaped : '[too long to show]').'".'.PHP_EOL.
				'Please try again.');
        } else {
            $reply = \React\Promise\resolve(null);
        }
        
        return $reply->then(function ($msg) use ($message, &$values, $promptLimit, &$prompts, &$answers) {
            if($msg !== null) {
                $prompts[] = $msg;
            }
            
            if(\count($prompts) > $promptLimit) {
                return array(
                    'value' => null,
                    'cancelled' => 'promptLimit',
                    'prompts' => $prompts,
                    'answers' => $answers
                );
            }
            
            // Get the user's response
            return $message->channel->collectMessages(function ($msg) use ($message) {
                return ($msg->author->id === $message->author->id);
            }, array(
                'max' => 1,
                'time' => $this->wait
            ))->then(function ($messages) use ($message, &$values, $promptLimit, &$prompts, &$answers) {
                if($messages->count() === 0) {
                    return array(
                        'value' => null,
                        'cancelled' => 'time',
                        'prompts' => $prompts,
                        'answers' => $answers
                    );
                }
                
                $msg = $messages->first();
                $answers[] = $msg;
                
                $value = $msg->content;
                
                if(\strtolower($value) === 'finish') {
                    return array(
                        'value' => $values,
                        'cancelled' => (\count($values) > 0 ? null : 'user'),
                        'prompts' => $prompts,
                        'answers' => $answers
                    );
                } elseif(\strtolower($value) === 'cancel') {
                    return array(
                        'value' => null,
                        'cancelled' => 'user',
                        'prompts' => $prompts,
                        'answers' => $answers
                    );
                }
                
                $validate = ($this->validate ? array($this, 'validate') : array($this->type, 'validate'))($value, $message, $this->type);
                if(!($validate instanceof \React\Promise\PromiseInterface)) {
                    $validate = \React\Promise\resolve($validate);
                }
                
                return $validate->then(function ($valid) use ($message, $value, &$values, $promptLimit, &$prompts, &$answers) {
                    if($valid === false) {
                        return $this->infiniteObtain($message, $value, $values, $promptLimit, $prompts, $answers, $valid);
                    }
                    
                    return ($this->parse ? array($this, 'parse') : array($this->type, 'parse'))($value, $message, $this->type);
                });
            }, function () {
                return array(
                    'value' => null,
                    'cancelled' => 'time',
                    'prompts' => array(),
                    'answers' => array()
                );
            });
        });
    }
}
