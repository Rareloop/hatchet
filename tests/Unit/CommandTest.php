<?php

namespace Rareloop\Hatchet\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\Command;
use Rareloop\Lumberjack\Application;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function can_create_a_command_with_a_name()
    {
        $app = new Application;
        $command = new CommandWithName($app);

        $this->assertSame('test:command', $command->getName());
    }

    /** @test */
    public function can_create_a_command_from_signature_variable()
    {
        $app = new Application;
        $command = new CommandWithSignature($app);

        $definition = $command->getDefinition();

        $this->assertSame('test:command', $command->getName());
        $this->assertTrue($definition->hasOption('option'));
    }

    /** @test */
    public function can_add_description_from_class_variable()
    {
        $app = new Application;
        $command = new CommandWithDescription($app);

        $this->assertSame('testing123', $command->getDescription());
    }
}

class CommandWithName extends Command
{
    protected $name = 'test:command';
}

class CommandWithSignature extends Command
{
    protected $signature = 'test:command {name} {--option}';
}

#[AsCommand(
    name: 'test:command',
    description: 'testing123'
)]
class CommandWithDescription extends Command
{
}
