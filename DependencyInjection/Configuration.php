<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
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
     * (non-PHPdoc)
     * @see \Symfony\Component\Config\Definition\ConfigurationInterface::getConfigTreeBuilder()
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_form');

        $this->addRecaptchaConfiguration($rootNode);
        $this->addTinyMCEConfiguration($rootNode);
        $this->addPlUploadConfiguration($rootNode);
        
        return $treeBuilder;
    }
    
    /**
     * Recaptcha configurations
     * 
     * @param ArrayNodeDefinition $rootNode
     * @return void
     */
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

    /**
     * Tinymce configurations
     * 
     * @param ArrayNodeDefinition $rootNode
     * @return void
     */
    private function addTinyMCEConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('tinymce')
                    ->canBeUnset()
                    ->children()
                        ->booleanNode('filemanager')->defaultFalse()->end()
                        ->scalarNode('tiny_mce_path_js')
                            ->isRequired(true)
                            ->beforeNormalization()
                                ->always()
                                ->then(function($v) {
                                    return trim($v, '/');
                                })
                            ->end()
                        ->end()
                        ->scalarNode('ajaxfilemanager_path_php')
                            ->defaultValue(null)
                            ->beforeNormalization()
                                ->always()
                                ->then(function($v) {
                                    return trim($v, '/');
                                })
                            ->end()
                        ->end()
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
    
    /**
     * Plupload configurations
     * 
     * @param ArrayNodeDefinition $rootNode
     * @return void
     */
    private function addPlUploadConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('plupload')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('runtimes')->defaultValue('html5,flash')->end()
                        ->scalarNode('plupload_flash_path_swf')
                            ->defaultNull()
                            ->beforeNormalization()
                                ->always()
                                ->then(function($v) {
                                    return trim($v, '/');
                                })
                            ->end()
                        ->end()
                        ->scalarNode('temporary_dir')
                            ->defaultValue('temp')
                            ->beforeNormalization()
                                ->always()
                                ->then(function($v) {
                                    return trim($v, '/');
                                })
                            ->end()
                        ->end()
                        ->scalarNode('max_upload_size')->defaultValue('4M')->end()
                        ->scalarNode('normalize_width')->defaultValue(1000)->end()
                        ->scalarNode('normalize_height')->defaultValue(1000)->end()
                        ->booleanNode('enable_version')->defaultFalse()->end()
                        ->arrayNode('image_options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled_button')->defaultValue(true)->end()
                                ->booleanNode('view_button')->defaultValue(true)->end()
                                ->booleanNode('crop_button')->defaultValue(true)->end()
                                ->booleanNode('meta_button')->defaultValue(true)->end()
                                ->booleanNode('rotate_button')->defaultValue(true)->end()
                                ->booleanNode('reset_button')->defaultValue(true)->end()
                            ->end()
                        ->end()
                        ->arrayNode('file_options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled_button')->defaultValue(true)->end()
                                ->booleanNode('meta_button')->defaultValue(true)->end()                  
                            ->end()
                        ->end()
                     ->end()
                ->end()
            ->end()
        ;
    }
}
