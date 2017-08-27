<?php
declare(strict_types=1);
namespace Alister\ReservedNamesBundle\Tests\DependencyInjection;

use Alister\ReservedNamesBundle\DependencyInjection\AlisterReservedNamesExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReservedNamesExtensionTest extends TestCase
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     * @var Alister\ReservedNamesBundle\DependencyInjection\AlisterReservedNamesExtension
     */
    private $extension;

    private $defaultConfig;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new AlisterReservedNamesExtension();

        $this->localAppConfig = [
            ['names' => ['a', 'PUYOWCBQYHZJXENBLZZJ']],
        ];
    }

    public function testShouldLoadDefaultConfiguration()
    {
        $this->extension->load($this->localAppConfig, $this->container);
        $this->assertDefaultConfigDefinition();

        $names = $this->container->getParameter('alister_reserved_names.names');
        // check from the 'localAppConfig' - in setUp(), above
        $this->assertArrayHasKey('a', $names);
        $this->assertArrayHasKey('puyowcbqyhzjxenblzzj', $names);
        // we lower-cased the 'names' that are in the local config (eg app/config.yml)
    }

    /**
     * Assert that the default config definition loads the given options.
     *
     * @param array $config
     */
    private function assertDefaultConfigDefinition()
    {
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $this->container);
        $this->assertTrue($this->container->hasParameter('alister_reserved_names.names'));

        $names = $this->container->getParameter('alister_reserved_names.names');
        $this->assertGreaterThanOrEqual(100, $names);
        $this->assertArrayHasKey('website', $names);    // from the default list

        $this->assertTrue($this->container->hasDefinition('alister_reserved_names.check'));
    }

    /* *
     * Assert that the named config definition extends the default profile and
     * loads the given options.
     *
     * @param string $name
     * @param array  $config
     * /
    private function assertConfigDefinition($name, array $config)
    {
        $this->assertTrue($this->container->hasDefinition('alister_reserved_names.config.' . $name));

        $definition = $this->container->getDefinition('alister_reserved_names.config.' . $name);
        $this->assertEquals('%alister_reserved_names.config.class%', $definition->getClass());
        $this->assertEquals('%alister_reserved_names.config.class%', $definition->getFactoryClass());
        $this->assertEquals('inherit', $definition->getFactoryMethod());

        $this->assertEquals(1, count($definition->getArguments()));
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $definition->getArgument(0));
        $this->assertEquals('alister_reserved_names.config.default', (string) $definition->getArgument(0));

        $calls = $definition->getMethodCalls();
        $this->assertEquals(1, count($calls));
        $this->assertEquals('loadArray', $calls[0][0]);
        $this->assertEquals(array($config), $calls[0][1]);
    }*/

    /*public function testShouldResolveServices()
    {
        $container = new ContainerBuilder;
        $extension = new AlisterReservedNamesExtension();

        $config = array(
            'simple' => array(
                'AutoFormat.Custom' => array('@service_container'),
            ),
        );

        $this->extension->load(array($config), $this->container);

        $definition = $this->container->getDefinition('alister_reserved_names.config.simple');
        $calls = $definition->getMethodCalls();

        $call = $calls[0];
        $this->assertSame('loadArray', $call[0]);

        $args = $call[1];

        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $args[0]['AutoFormat.Custom'][0]);
    }*/
}
