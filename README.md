# Hatchet - CLI for Lumberjack
![CI](https://travis-ci.org/Rareloop/hatchet.svg?branch=master)
![Coveralls](https://coveralls.io/repos/github/Rareloop/hatchet/badge.svg?branch=master)

## Installation
```
composer require rareloop/hatchet
```

Once installed you need to copy the `hatchet` file into your Lumberjack theme directory. 

*It is assuming you're using Lumberjack inside Bedrock. If not, you may need to make some changes to paths in the `hatchet` file*

## Basic Usage
You can now access the Hatchet CLI from inside your Lumberjack theme directory:

### To show available commands

```
php hatchet list
```

### To run a command
For a given command called `test:command` you would run the following:

```
php hatchet test:command
```

### Get additional help about a command
For a given command called `test:command` you would run the following:

```
php hatchet help test:command
```

## Adding Commands
To add additional commands to Hatchet add them to `config/hatchet.php` (create the file if it doesn't exist).

```php
// config/hatchet.php

return [
    'commands' => [
        MyCommand::class,
    ],
];
```

## Writing Commands
Create a subclass of `Rareloop\Hatchet\Commands\Command`:

```php
namespace MyNamespace;

use Rareloop\Hatchet\Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerMake extends Command
{
    protected $signature = 'test:command {paramName : The description of the parameter}';

    protected $description = 'A description of the command';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Command implementation
    }
}
```

Hatchet uses the same `$signature` syntax as Laravel, [see here](https://laravel.com/docs/5.6/artisan#writing-commands) for more information.

Hatchet `Command` is a subclass of Symfony's `Command` object, for more information on how to implement the `execute()` function [see here](https://symfony.com/doc/current/console.html).
