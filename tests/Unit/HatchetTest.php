<?php

namespace Rareloop\Hatchet\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\Command;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\RegisterCommands;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Bootstrappers\BootProviders;
use Rareloop\Lumberjack\Bootstrappers\LoadConfiguration;
use Rareloop\Lumberjack\Bootstrappers\RegisterExceptionHandler;
use Rareloop\Lumberjack\Bootstrappers\RegisterFacades;
use Rareloop\Lumberjack\Bootstrappers\RegisterProviders;
use Rareloop\Lumberjack\Config;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HatchetTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /** @test */
    public function bootstrap_should_pass_bootstrappers_to_app()
    {
        $app = Mockery::mock(Application::class.'[bootstrapWith]');
        $app->shouldReceive('bootstrapWith')->with([
            RegisterExceptionHandler::class,
            LoadConfiguration::class,
            RegisterFacades::class,
            RegisterProviders::class,
            BootProviders::class,
            RegisterCommands::class,
        ])->once();

        $kernal = new Hatchet($app);
        $kernal->bootstrap();
    }

    /** @test */
    public function hatchet_is_registered_in_the_container_when_created()
    {
        $app = new Application;
        $kernal = new Hatchet($app);

        $this->assertInstanceOf(Hatchet::class, $app->get(Hatchet::class));
        $this->assertSame($kernal, $app->get(Hatchet::class));
    }

    /** @test */
    public function can_access_console()
    {
        $app = new Application;
        $kernal = new Hatchet($app);

        $console = $kernal->console();

        $this->assertInstanceOf(ConsoleApplication::class, $console);
    }

    /** @test */
    public function default_commands_are_registered_on_the_console()
    {
        $app = Mockery::mock(Application::class.'[bootstrapWith]');
        $app->shouldReceive('bootstrapWith');
        $kernal = Mockery::mock(Hatchet::class.'[defaultCommands]', [$app]);
        $kernal->shouldReceive('defaultCommands')->once()->andReturn([
            TestCommand::class,
        ]);

        $kernal->bootstrap();

        $this->assertTrue($kernal->console()->has((new TestCommand($app))->getName()));
    }
}

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('test:command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        return false;
    }
}
