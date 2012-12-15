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

use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\DefinitionDecorator;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Config\FileLocator;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronFormExtension extends Extension
{

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\DependencyInjection\Extension\ExtensionInterface::load()
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        if (isset($config['recaptcha'])){
            $container
                ->getDefinition('neutron_form.form.type.recaptcha')
                ->addArgument($config['recaptcha'])
                ->addTag('form.type', array('alias' => 'neutron_recaptcha'))
            ;
            
            $container
                ->getDefinition('neutron_form.validator.constraint.recaptcha')
                ->addArgument($config['recaptcha'])
                ->addTag('validator.constraint_validator', array('alias' => 'neutron_form_recaptcha_validator'))
            ;
        }
        
        if (isset($config['tinymce'])){
            $container
                ->getDefinition('neutron_form.form.type.tinymce')
                ->addArgument($config['tinymce'])
                ->addTag('form.type', array('alias' => 'neutron_tinymce'))
            ;
        }
        
        if (isset($config['plupload'])){

            $container->setParameter('neutron_form.plupload.configs', $config['plupload']);
            $this->loadImageUploadConfigurations($container, $config['plupload']);
            $this->loadFileUploadConfigurations($container, $config['plupload']);

        }
        
        $this->loadExtendedTypes('neutron_form.form.type.buttonset', 'neutron_buttonset', $container);
        $this->loadExtendedTypes('neutron_form.form.type.select2', 'neutron_select2', $container);
        
    }
    
    /**
     * Loads image upload configurations
     * 
     * @param ContainerBuilder $container
     * @param array $configs
     */
    protected function loadImageUploadConfigurations(ContainerBuilder $container, array $configs)
    {
        $container
            ->getDefinition('neutron_form.form.type.image_upload')
            ->addArgument($configs)
            ->addTag('form.type', array('alias' => 'neutron_image_upload'))
        ;
        
        $container
            ->getDefinition('neutron_form.form.type.multi_image_upload_collection')
            ->addArgument($configs)
            ->addTag('form.type', array('alias' => 'neutron_multi_image_upload_collection'))
        ;
        
        $container
            ->getDefinition('neutron_form.form.type.multi_image_upload')
            ->addTag('form.type', array('alias' => 'neutron_multi_image_upload'))
        ;
        
        $container
            ->getDefinition('neutron_form.doctrine.orm.event_subscriber.image_upload')
            ->addArgument($configs['enable_version'])
            ->addTag('doctrine.event_subscriber', array('connection' => 'default'))
        ;
        
        $container
            ->getDefinition('neutron_form.manager.image_manager')
            ->addMethodCall('setTempDir', array($configs['temporary_dir']))
        ;
        
        $container
            ->getDefinition('neutron_form.twig.extension.form')
            ->addTag('twig.extension')
        ;
    }
    
    /**
     *  Loads image upload configurations
     * 
     * @param ContainerBuilder $container
     * @param array $configs
     */
    protected function loadFileUploadConfigurations(ContainerBuilder $container, array $configs)
    {
        $container
            ->getDefinition('neutron_form.form.type.file_upload')
            ->addArgument($configs)
            ->addTag('form.type', array('alias' => 'neutron_file_upload'))
        ;
        
        $container
            ->getDefinition('neutron_form.form.type.multi_file_upload_collection')
            ->addArgument($configs)
            ->addTag('form.type', array('alias' => 'neutron_multi_file_upload_collection'))
        ;
        
        $container
            ->getDefinition('neutron_form.form.type.multi_file_upload')
            ->addTag('form.type', array('alias' => 'neutron_multi_file_upload'))
        ;
        
        $container
            ->getDefinition('neutron_form.doctrine.orm.event_subscriber.file_upload')
            ->addArgument($configs['enable_version'])
            ->addTag('doctrine.event_subscriber', array('connection' => 'default'))
        ;
        
        $container
            ->getDefinition('neutron_form.manager.file_manager')
            ->addMethodCall('setTempDir', array($configs['temporary_dir']))
        ;
        
    }
    
    /**
     * Loads extended form types.
     *
     * @param string           $serviceId Id of the abstract service
     * @param string           $name      Name of the type
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function loadExtendedTypes($serviceId, $name, ContainerBuilder $container)
    {
        foreach (array('choice', 'language', 'country', 'timezone', 'locale', 'entity', 'ajax') as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef
                ->addArgument($type)
                ->addTag('form.type', array('alias' => $name. '_' . $type))
            ;
    
            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }
}
