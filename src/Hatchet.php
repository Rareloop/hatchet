<?php

namespace Rareloop\Hatchet;

use DI\ContainerBuilder;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Bootstrappers\BootProviders;
use Rareloop\Lumberjack\Bootstrappers\LoadConfiguration;
use Rareloop\Lumberjack\Bootstrappers\RegisterExceptionHandler;
use Rareloop\Lumberjack\Bootstrappers\RegisterFacades;
use Rareloop\Lumberjack\Bootstrappers\RegisterProviders;
use Symfony\Component\Console\Application as ConsoleApplication;

use Rareloop\Hatchet\Commands\PostTypeMake;
use Rareloop\Hatchet\Commands\ControllerMake;

class Hatchet
{
    private $app;

    protected $bootstrappers = [
        LoadConfiguration::class,
        RegisterFacades::class,
        RegisterProviders::class,
        BootProviders::class,
        RegisterCommands::class,
    ];

    protected $defaultCommands = [
        ControllerMake::class,
        PostTypeMake::class,
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->consoleApp = $this->app->make(ConsoleApplication::class, ['name' => 'Hatchet - Lumberjack CLI']);

        $this->app->bind(Hatchet::class, $this);
    }

    public function bootstrap()
    {
        $this->loadDefaultCommands();
        $this->app->bootstrapWith($this->bootstrappers());
    }

    protected function loadDefaultCommands()
    {
        foreach ($this->defaultCommands() as $command) {
            $this->consoleApp->add($this->app->make($command));
        }
    }

    public function run()
    {
        $this->consoleApp->run();
    }

    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }

    public function console()
    {
        return $this->consoleApp;
    }

    public function defaultCommands()
    {
        return $this->defaultCommands;
    }
}
