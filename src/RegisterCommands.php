<?php

namespace Rareloop\Hatchet;

use Rareloop\Hatchet\Hatchet;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;

class RegisterCommands
{
    public function bootstrap(Application $app, Config $config, Hatchet $hatchet)
    {
        $commands = $config->get('hatchet.commands', []);

        foreach ($commands as $command) {
            $hatchet->console()->add($app->make($command));
        }
    }
}
