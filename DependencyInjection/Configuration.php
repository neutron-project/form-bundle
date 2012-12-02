<?php

namespace Neutron\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_form');

        $this->addRecaptchaConfiguration($rootNode);
        
        return $treeBuilder;
    }
    
    private function addRecaptchaConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('recaptcha')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('public_key')->isRequired()->end()
                        ->scalarNode('private_key')->isRequired()->end()
                        ->scalarNode('verify_url')->defaultValue('http://www.google.com/recaptcha/api/verify')->end()
                        ->scalarNode('server_url')->defaultValue('https://api-secure.recaptcha.net')->end()
                        ->scalarNode('theme')->defaultValue('red')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
