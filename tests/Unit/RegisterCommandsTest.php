<?php

namespace Rareloop\Hatchet\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\Command;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\RegisterCommands;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;

class RegisterCommandsTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /** @test */
    public function additional_commands_are_registered_from_config()
    {

        $config = new Config;
        $config->set('hatchet.commands', [
            AnotherTestCommand::class,
        ]);

        $app = new Application;
        $app->bind(Config::class, $config);

        $kernal = new Hatchet($app);

        $bootstrapper = new RegisterCommands($config);
        $bootstrapper->bootstrap($app, $config, $kernal);

        $this->assertTrue($kernal->console()->has((new AnotherTestCommand($app))->getName()));
    }
}

class AnotherTestCommand extends Command
{
    protected function configure()
    {
        $this->setName('test:command');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {}
}
