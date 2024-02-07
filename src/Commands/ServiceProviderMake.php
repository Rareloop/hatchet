<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\MakeFromStubCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:provider {name : The class name of the ServiceProvider}',
    description: 'Create a ServiceProvider',
)]
class ServiceProviderMake extends MakeFromStubCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $stub = file_get_contents(__DIR__ . '/stubs/ServiceProvider.stub');
        $stub = str_replace('DummyServiceProvider', $name, $stub);

        $this->createFile('app/Providers/'.$name.'.php', $stub);
    }
}
