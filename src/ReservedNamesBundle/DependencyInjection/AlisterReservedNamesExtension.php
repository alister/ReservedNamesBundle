<?php
namespace Alister\ReservedNamesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AlisterReservedNamesExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter(
            $this->getAlias().'.names',
            $this->collectAllNames($config['names'])
        );
    }

    public function collectAllNames($appNames = [])
    {
        $appNames = array_fill_keys($appNames, 1);
        $appNames = array_change_key_case($appNames, CASE_LOWER);

        $path = new FileLocator(__DIR__.'/../Resources/config/');
        $cfgNames = require $path->locate('reserved_names.php');

        return array_merge($cfgNames, $appNames);
    }
}
