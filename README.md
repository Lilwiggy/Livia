# Livia [![Build Status](https://scrutinizer-ci.com/g/CharlotteDunois/Livia/badges/build.png?b=master)](https://scrutinizer-ci.com/g/CharlotteDunois/Livia/build-status/master)
Livia is a Discord Bot framework, which utilizes Yasmin, the Discord API library.

Livia is designed after discord.js-Commando, as I think the design is great.

# Built-in Argument Types
Livia ships with a few argument types you can use for your command arguments. Here's a list:

* boolean
* channel
* command
* command-or-group
* float
* group
* integer
* member
* role
* string
* user

# Built-in Commands
The following commands are coming with Livia:

* disable (disable commands/groups)
* enable (enable commands/groups)
* load (load new commands)
* reload (reload command(s))
* unload (unload a command)
* eval (evaluates PHP code)
* help (Displays a help message in DM)
* ping (Calculates the bot's latency)
* prefix (Gets or sets the bot's prefix in the guild/globally)

# Making Commands
Livia features Commands Reloading, which requires you to return an anonymous function in your command file, which returns a new anonymous class.

Example:
```php
// /rootBot/commands/moderation/ban.js

// Livia forces you to use lowercase command name and group ID.
// (moderation = group ID, ban = command name)

// Livia will automatically call the anonymous function and pass the LiviaClient instance.
return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
            parent::__construct($client, array(
                'name' => 'ban',
                'aliases' => array(),
                'group' => 'moderation',
                'description' => 'Bans an user.',
                'guildOnly' => true,
                'throttling' => array( // Throttling is per-user
                    'usages' => 2,
                    'duration' => 3
                ),
                'args' => array(
                    array(
                        'key' => 'user',
                        'prompt' => 'Which user do you wanna ban?',
                        'type' => 'member'
                    )
                )
            ));
        }
        
        // Even if you don't use all arguments, you are forced to match use that method signature.
        function run(\CharlotteDunois\Livia\CommandMessage $message, array $args,
                      bool $fromPattern) {
            // Do what the command has to do.
            // You are free to return a Promise, or do all-synchronous tasks synchronously.
            
            // If you send any messages (doesn't matter how many),
            // return (resolve) the Message instance, or an array of Message instances.
            // Promises are getting automatically resolved.
            
            return $args['user']->ban()->then(function () use ($message) {
                return $message->reply('The user got banned!');
            });
        }
    };
};
```

# Example

```php
require_once(IN_DIR.'/vendor/autoload.php');

$loop = \React\EventLoop\Factory::create();
$client = new \CharlotteDunois\Livia\LiviaClient(array(
    'owners' => array('YOUR_USER_ID'),
    'unknownCommandResponse' => false
), $loop);

// Registers default commands, command groups and argument types
$client->registry->registerDefaults();

$client->on('ready', function () use ($client) {
    echo 'Logged in as '.$client->user->tag.' created on '.
           $client->user->createdAt->format('d.m.Y H:i:s').PHP_EOL;
});

$client->login('YOUR_TOKEN');
$loop->run();
```

# Documentation
https://charlottedunois.github.io/Livia/
