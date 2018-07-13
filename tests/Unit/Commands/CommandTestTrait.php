<?php

namespace Rareloop\Hatchet\Test\Unit\Commands;

use Mockery;
use Rareloop\Hatchet\Commands\Command;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Lumberjack\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use org\bovigo\vfs\vfsStream;

trait CommandTestTrait
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected $rootFileSystem;
    protected $vfsStreamDirectoryName = 'exampleDir';

    public function setUp()
    {
        parent::setup();
        $this->rootFileSystem = vfsStream::setup($this->vfsStreamDirectoryName, null, [
            'app' => [
                'Http' => [
                    'Controllers' => [],
                ],
            ],
        ]);
    }

    protected function callHatchetCommand(Hatchet $hatchet, $name, array $params = [])
    {
        $hatchet->console()->setAutoExit(false);

        $input = new ArrayInput([
            'command' => $name,
        ] + $params);

        $output = new BufferedOutput();
        $hatchet->console()->run($input, $output);

        return $output;
    }

    protected function appWithMockBasePath()
    {
        $app = Mockery::mock(Application::class.'[basePath]');
        $app->shouldReceive('basePath')->andReturn(vfsStream::url($this->vfsStreamDirectoryName));

        return $app;
    }

    protected function assertMockPath($path)
    {
        return $this->assertTrue($this->rootFileSystem->hasChild($path));
    }

    protected function assertMockPathMissing($path)
    {
        return $this->assertFalse($this->rootFileSystem->hasChild($path));
    }

    protected function requireMockFile($path)
    {
        require vfsStream::url($this->vfsStreamDirectoryName . '/' . $path);
    }
}
