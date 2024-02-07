<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\MakeFromStubCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:viewmodel {name : The class name of the View Model}',
    description: 'Create a ViewModel',
)]
class ViewModelMake extends MakeFromStubCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $stub = file_get_contents(__DIR__ . '/stubs/ViewModel.stub');
        $stub = str_replace('DummyViewModel', $name, $stub);

        $this->createFile('app/ViewModels/'.$name.'.php', $stub);
    }
}
