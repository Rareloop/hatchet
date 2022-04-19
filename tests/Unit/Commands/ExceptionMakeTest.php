<?php

namespace Rareloop\Hatchet\Test\Commands;

use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\ExceptionMake;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\Test\Unit\Commands\CommandTestTrait;
use Exception;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ExceptionMakeTest extends TestCase
{
    use CommandTestTrait;

    /** @test */
    public function can_create_a_viewmodel_with_a_name()
    {
        $app = $this->appWithMockBasePath();
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(ExceptionMake::class));

        $output = $this->callHatchetCommand($hatchet, 'make:exception', [
            'name' => 'MyException',
        ]);

        // Assert the file was created
        $relativePath = 'app/Exceptions/MyException.php';
        $this->assertMockPath($relativePath);
        $this->assertStringNotContainsString('DummyException', $this->getMockFileContents($relativePath));
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $exception = new \App\Exceptions\MyException;
        $this->assertInstanceOf(\App\Exceptions\MyException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
