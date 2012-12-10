<?php
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
     * {@inheritDoc}
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
            
            $container
                ->getDefinition('neutron_form.form.type.image_upload')
                ->addArgument($config['plupload'])
                ->addTag('form.type', array('alias' => 'neutron_image_upload'))
            ;
            
            $container
                ->getDefinition('neutron_form.form.type.multi_image_upload_collection')
                ->addArgument($config['plupload'])
                ->addTag('form.type', array('alias' => 'neutron_multi_image_upload_collection'))
            ;
            
            $container
                ->getDefinition('neutron_form.form.type.multi_image_upload')
                ->addTag('form.type', array('alias' => 'neutron_multi_image_upload'))
            ;

            $container
                ->getDefinition('neutron_form.doctrine.orm.event_subscriber.image_upload')
                ->addArgument($config['plupload']['enable_version'])
                ->addTag('doctrine.event_subscriber', array('connection' => 'default'))
            ;
            
            $container
                ->getDefinition('neutron_form.manager.image_manager')
                ->addMethodCall('setTempDir', array($config['plupload']['temporary_dir']))
            ;
            
            $container
                ->getDefinition('neutron_form.twig.extension.image')
                ->addTag('twig.extension')
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
