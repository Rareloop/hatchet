<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerMake extends Command
{
    protected $signature = 'make:controller {name : The class name of the controller}';

    protected $description = 'Create a controller';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $stub = file_get_contents(__DIR__ . '/stubs/controller.stub');

        $stub = str_replace('DummyController', $name, $stub);

        file_put_contents($this->app->basePath() . '/app/Http/Controllers/'.$name.'.php', $stub);
    }
}
