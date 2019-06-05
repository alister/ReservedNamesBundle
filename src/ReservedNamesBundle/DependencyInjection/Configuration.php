<?php
namespace Alister\ReservedNamesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('alister_reserved_names');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('alister_reserved_names');
        }

        $rootNode
            ->children()
                //->scalarNode('clean_username')->defaultValue('xiidea.clean_username.class')->end()
                ->variableNode('names')
                    ->defaultValue(['reservedname', 'woz'])
                    ->info('multiple names that will not be allowed as new users')
                    ->example('billgates, stevewoz')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
