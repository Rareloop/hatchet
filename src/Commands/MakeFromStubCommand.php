<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\Command;

abstract class MakeFromStubCommand extends Command
{
    protected function createFile($relativePath, $contents)
    {
        $absolutePath = $this->app->basePath() . '/' . $relativePath;
        $directory = dirname($absolutePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0754, true);
        }

        file_put_contents($absolutePath, $contents);
    }
}
