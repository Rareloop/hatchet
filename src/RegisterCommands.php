<?php

namespace Rareloop\Hatchet;

use Rareloop\Hatchet\Hatchet;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;

class RegisterCommands
{
    public function bootstrap(Application $app)
    {
        $config = $app->get(Config::class);
        $hatchet = $app->get(Hatchet::class);

        $commands = $config->get('hatchet.commands', []);

        foreach ($commands as $command) {
            $hatchet->console()->add($app->make($command));
        }
    }
}
