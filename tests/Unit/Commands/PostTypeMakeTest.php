<?php

namespace Rareloop\Hatchet\Test\Commands;

use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\PostTypeMake;
use Rareloop\Hatchet\Test\Unit\Commands\CommandTestTrait;
use Rareloop\Lumberjack\Post;
use Symfony\Component\Console\Tester\CommandTester;
use phpmock\MockBuilder;
use ReflectionClass;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PostMakeTest extends TestCase
{
    use CommandTestTrait;

    /** @test */
    public function can_create_a_postype()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                ' ', // Features
                ' ', // Auto Register?
            ]
        );

        // Assert the file was created
        $relativePath = 'app/PostTypes/Product.php';
        $this->assertMockPath($relativePath);
        $this->assertStringNotContainsString('DummyPostType', $this->getMockFileContents($relativePath));
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $this->assertTrue($class->isSubclassOf(Post::class));
        $this->assertSame('product', \App\PostTypes\Product::getPostType());

        $data = $this->getPostTypeConfig($class);
        $this->assertSame('Products', $data['labels']['name']);
        $this->assertSame('Product', $data['labels']['singular_name']);
        $this->assertSame('product', $data['rewrite']['slug']);

        // Features
        $this->assertTrue($data['has_archive']);
        $this->assertContains('title', $data['supports']);
        $this->assertContains('editor', $data['supports']);
        $this->assertContains('thumbnail', $data['supports']);
        $this->assertContains('author', $data['supports']);
        $this->assertContains('revisions', $data['supports']);

        // Registered
        $config = $this->requireMockFile('config/posttypes.php');
        $this->assertContains(\App\PostTypes\Product::class, $config['register']);
    }

    /** @test */
    public function can_create_a_postype_and_change_plural()
    {
        $this->executeCommand(
            'Product',
            [
                'Pluralized', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                ' ', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $data = $this->getPostTypeConfig($class);
        $this->assertSame('Pluralized', $data['labels']['name']);
    }

    /** @test */
    public function can_create_a_postype_and_change_wp_post_name()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                'wp-post-name', // WordPress Post Name
                ' ', // Slug
                ' ', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $this->assertSame('wp-post-name', \App\PostTypes\Product::getPostType());
    }

    /** @test */
    public function can_create_a_postype_and_change_slug()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                'wp-slug', // Slug
                ' ', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $data = $this->getPostTypeConfig($class);
        $this->assertSame('wp-slug', $data['rewrite']['slug']);
    }

    /** @test */
    public function can_create_a_postype_and_disable_archives()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                '0,1,2', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $data = $this->getPostTypeConfig($class);
        $this->assertFalse($data['has_archive']);
    }

    /** @test */
    public function can_create_a_postype_and_disable_content_editor()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                '1,2,3', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $data = $this->getPostTypeConfig($class);
        $this->assertNotContains('editor', $data['supports']);
    }

    /** @test */
    public function can_create_a_postype_and_disable_revisions()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                '0,1,3', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $data = $this->getPostTypeConfig($class);
        $this->assertNotContains('revisions', $data['supports']);
    }

    /** @test */
    public function can_create_a_postype_and_disable_thumbnails()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                '0,2,3', // Features
                ' ', // Auto Register?
            ]
        );

        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $class = new ReflectionClass(\App\PostTypes\Product::class);
        $data = $this->getPostTypeConfig($class);
        $this->assertNotContains('thumbnail', $data['supports']);
    }

    /** @test */
    public function can_create_a_postype_and_not_register_with_config()
    {
        $this->executeCommand(
            'Product',
            [
                ' ', // Plural
                ' ', // WordPress Post Name
                ' ', // Slug
                ' ', // Features
                'n', // Auto Register?
            ]
        );

        // Assert the file was created
        $relativePath = 'app/PostTypes/Product.php';
        $this->requireMockFile($relativePath);

        // Registered
        $config = $this->requireMockFile('config/posttypes.php');
        $this->assertNotContains(\App\PostTypes\Product::class, $config['register']);
    }

    protected function executeCommand($name, array $input)
    {
        $this->mockWordPressLanguageFunctions();
        $app = $this->appWithMockBasePath();
        mkdir($this->getMockPath('config'));
        file_put_contents($this->getMockPath('config/posttypes.php'), "<?php
return [
    /**
     * List all the sub-classes of Rareloop\Lumberjack\Post in your app that you wish to
     * automatically register with WordPress as part of the bootstrap process.
     */
    'register' => [
    ],
];");

        $command = new PostTypeMake($app);
        $commandTester = new CommandTester($command);

        $commandTester->setInputs($input);

        $commandTester->execute(['name' => $name]);
    }

    protected function getPostTypeConfig(ReflectionClass $class)
    {
        $method = $class->getMethod('getPostTypeConfig');
        $method->setAccessible(true);
        return $method->invoke(null);
    }

    protected function mockWordPressLanguageFunctions()
    {
        $builder = new MockBuilder();
        $builder->setNamespace('App\PostTypes')
            ->setName("__")
            ->setFunction(
                function ($input) {
                    return $input;
                }
            );

        $mock = $builder->build();
        $mock->enable();
    }
}
