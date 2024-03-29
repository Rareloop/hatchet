<?php

namespace Rareloop\Hatchet\Test\Commands;

use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\RouteList;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\Test\Unit\Commands\CommandTestTrait;
use Rareloop\Lumberjack\Application;
use Rareloop\Router\Router;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RouteListTest extends TestCase
{
    use CommandTestTrait;

    /** @test */
    public function can_list_routes_with_closure()
    {
        $app = new Application;
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(RouteList::class));
        $router = new Router($app);
        $app->bind(Router::class, $router);
        $router->get('/test/123', function () {
        })->name('MyRouteName');

        $output = $this->callHatchetCommand($hatchet, 'route:list');
        $output = $output->fetch();

        $this->assertStringContainsString('/test/123', $output);
        $this->assertStringContainsString('Closure', $output);
        $this->assertStringContainsString('GET', $output);
        $this->assertStringContainsString('MyRouteName', $output);
    }

    /** @test */
    public function can_list_routes_with_callable()
    {
        $app = new Application;
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(RouteList::class));
        $router = new Router($app);
        $app->bind(Router::class, $router);
        $router->get('/test/123', [RouteListTestController::class, 'testStatic']);

        $output = $this->callHatchetCommand($hatchet, 'route:list');
        $output = $output->fetch();

        $this->assertStringContainsString('/test/123', $output);
        $this->assertStringContainsString(RouteListTestController::class, $output);
        $this->assertStringContainsString('GET', $output);
    }

    /** @test */
    public function can_list_multiple_methods()
    {
        $app = new Application;
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(RouteList::class));
        $router = new Router($app);
        $app->bind(Router::class, $router);
        $router->map(['get', 'post'], '/test/123', function () {
        });

        $output = $this->callHatchetCommand($hatchet, 'route:list');
        $output = $output->fetch();

        $this->assertStringContainsString('GET|POST', $output);
    }
}

class RouteListTestController
{
    public function test()
    {
    }
    public static function testStatic()
    {
    }
}
