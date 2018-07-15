<?php

namespace Rareloop\Hatchet\Test\Commands;

use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\ServiceProviderMake;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\Test\Unit\Commands\CommandTestTrait;
use Rareloop\Lumberjack\Providers\ServiceProvider;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ServiceProviderMakeTest extends TestCase
{
    use CommandTestTrait;

    /** @test */
    public function can_create_a_viewmodel_with_a_name()
    {
        $app = $this->appWithMockBasePath();
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(ServiceProviderMake::class));

        $output = $this->callHatchetCommand($hatchet, 'make:provider', [
            'name' => 'MyServiceProvider',
        ]);

        // Assert the file was created
        $relativePath = 'app/Providers/MyServiceProvider.php';
        $this->assertMockPath($relativePath);
        $this->assertNotContains('DummyServiceProvider', $this->getMockFileContents($relativePath));
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $provider = new \App\Providers\MyServiceProvider($app);
        $this->assertInstanceOf(\App\Providers\MyServiceProvider::class, $provider);
        $this->assertInstanceOf(ServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'register'), 'Class does not have method `register`');
        $this->assertTrue(method_exists($provider, 'boot'), 'Class does not have method `boot`');
    }
}
