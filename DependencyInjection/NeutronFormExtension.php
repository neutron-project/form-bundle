<?php

namespace Neutron\FormBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        if (isset($config['recaptcha'])){
            $container->setParameter('neutron_form.recaptcha.configs', $config['recaptcha']);
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
        
        $this->loadExtendedTypes('neutron_form.form.type.buttonset', 'neutron_buttonset', $container);
        $this->loadExtendedTypes('neutron_form.form.type.select2', 'neutron_select2', $container);
        
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
