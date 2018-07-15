<?php

namespace Rareloop\Hatchet\Test\Commands;

use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\ViewModelMake;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\Test\Unit\Commands\CommandTestTrait;
use Rareloop\Lumberjack\ViewModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ViewModelMakeTest extends TestCase
{
    use CommandTestTrait;

    /** @test */
    public function can_create_a_viewmodel_with_a_name()
    {
        $app = $this->appWithMockBasePath();
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(ViewModelMake::class));

        $output = $this->callHatchetCommand($hatchet, 'make:viewmodel', [
            'name' => 'MyViewModel',
        ]);

        // Assert the file was created
        $relativePath = 'app/ViewModels/MyViewModel.php';
        $this->assertMockPath($relativePath);
        $this->assertNotContains('DummyViewModel', $this->getMockFileContents($relativePath));
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $viewModel = new \App\ViewModels\MyViewModel;
        $this->assertInstanceOf(\App\ViewModels\MyViewModel::class, $viewModel);
        $this->assertInstanceOf(ViewModel::class, $viewModel);
    }
}
