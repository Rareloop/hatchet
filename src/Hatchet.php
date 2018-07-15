<?php

namespace Rareloop\Hatchet;

use DI\ContainerBuilder;
use Rareloop\Hatchet\Commands\ControllerMake;
use Rareloop\Hatchet\Commands\ExceptionMake;
use Rareloop\Hatchet\Commands\RouteList;
use Rareloop\Hatchet\Commands\ServiceProviderMake;
use Rareloop\Hatchet\Commands\ViewModelMake;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Bootstrappers\BootProviders;
use Rareloop\Lumberjack\Bootstrappers\LoadConfiguration;
use Rareloop\Lumberjack\Bootstrappers\RegisterExceptionHandler;
use Rareloop\Lumberjack\Bootstrappers\RegisterFacades;
use Rareloop\Lumberjack\Bootstrappers\RegisterProviders;
use Symfony\Component\Console\Application as ConsoleApplication;

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
        ExceptionMake::class,
        ServiceProviderMake::class,
        ViewModelMake::class,
        RouteList::class,
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
