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
        $this->addTinyMCEConfiguration($rootNode);
        
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

    private function addTinyMCEConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('tinymce')
                    ->canBeUnset()
                    ->children()
                        ->booleanNode('filemanager')->defaultFalse()->end()
                        ->scalarNode('tiny_mce_path_js')->isRequired(true)->end()
                        ->scalarNode('ajaxfilemanager_path_php')->defaultValue(null)->end()
                        ->variableNode('security')
                            ->defaultValue(array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN'))->end()
                        ->scalarNode('theme')
                            ->defaultValue('advanced')
                            ->validate()
                                ->ifNotInArray(array('advanced', 'simple'))
                                ->thenInvalid('The theme %s is not supported. Please choose one of '.json_encode(array('advanced', 'simple')))
                            ->end()
                        ->end()
                        ->scalarNode('skin')
                            ->defaultValue('default')
                            ->validate()
                                ->ifNotInArray(array('default', 'o2k7'))
                                ->thenInvalid('The skin %s is not supported. Please choose one of '.json_encode(array('default', 'o2k7')))
                            ->end()
                        ->end()
                        ->scalarNode('skin_variant')
                            ->defaultValue('silver')
                            ->validate()
                                ->ifNotInArray(array('silver', 'black'))
                                ->thenInvalid('The skin_variant %s is not supported. Please choose one of '.json_encode(array('silver', 'black')))
                            ->end()
                        ->end()
                        ->scalarNode('width')->defaultValue('70%')->end()
                        ->scalarNode('height')->defaultValue(300)->end()
                        ->scalarNode('dialog_type')
                            ->defaultValue('window')
                            ->validate()
                                ->ifNotInArray(array('window', 'modal'))
                                ->thenInvalid('The dialog %s is not supported. Please choose one of '.json_encode(array('window', 'modal')))
                            ->end()
                        ->end()
                        ->scalarNode('content_css')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
