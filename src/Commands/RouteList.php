<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\Command;
use Rareloop\Router\Router;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteList extends Command
{
    protected $signature = 'route:list';

    protected $description = 'List all registered routes';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = $this->app->get(Router::class);

        $rows = collect($router->getRoutes())->map(function ($route) {
            return [
                strtoupper(implode('|', $route->getMethods())),
                $route->getUri(),
                $route->getName(),
                $route->getActionName(),
            ];
        })->toArray();

        $table = new Table($output);
        $table->setHeaders(['Method', 'URI', 'Name', 'Action'])->setRows($rows);

        $table->render();
    }
}
