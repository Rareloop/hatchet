<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\MakeFromStubCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServiceProviderMake extends MakeFromStubCommand
{
    protected $signature = 'make:provider {name : The class name of the ServiceProvider}';

    protected $description = 'Create a ServiceProvider';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $stub = file_get_contents(__DIR__ . '/stubs/ServiceProvider.stub');
        $stub = str_replace('DummyServiceProvider', $name, $stub);

        $this->createFile('app/Providers/'.$name.'.php', $stub);
    }
}
