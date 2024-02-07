<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\MakeFromStubCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerMake extends MakeFromStubCommand
{
    protected $signature = 'make:controller {name : The class name of the Controller}';

    protected $description = 'Create a Controller';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $stub = file_get_contents(__DIR__ . '/stubs/Controller.stub');
        $stub = str_replace('DummyController', $name, $stub);

        $this->createFile('app/Http/Controllers/'.$name.'.php', $stub);
        return self::SUCCESS;
    }
}
