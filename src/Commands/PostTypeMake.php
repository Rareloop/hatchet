<?php

namespace Rareloop\Hatchet\Commands;

use Rareloop\Hatchet\Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Inflector\Inflector;

class PostTypeMake extends Command
{
    protected $signature = 'make:posttype {name : The singular name of the post type}';

    protected $description = 'Create a post type';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $stub = file_get_contents(__DIR__ . '/stubs/post-type.stub');

        $stub = str_replace('{{slug}}', Inflector::tableize($name), $stub);
        $stub = str_replace('{{class_name}}', Inflector::classify($name), $stub);
        $stub = str_replace('{{plural_name}}', Inflector::pluralize($name), $stub);
        $stub = str_replace('{{singular_name}}', Inflector::singularize($name), $stub);

        file_put_contents($this->app->basePath() . '/app/PostTypes/'.$name.'.php', $stub);
    }
}
