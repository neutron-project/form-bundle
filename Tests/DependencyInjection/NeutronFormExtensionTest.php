<?php
namespace Neutron\FormBundle\Tests\DependencyInjection;

use Neutron\FormBundle\DependencyInjection\NeutronFormExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class NeutronFormExtesionTest extends BaseTestCase
{
    public function testRecaptcha ()
    {
        $container = new ContainerBuilder();
        $loader = new NeutronFormExtension();
        $loader->load(array(array(
            'recaptcha' => array(
                'public_key' => 'xxx',
                'private_key' => 'xxx'
            )
        )), $container);
    
        $this->assertTrue($container->hasDefinition('neutron_form.form.type.recaptcha'));
        $this->assertTrue($container->getDefinition('neutron_form.form.type.recaptcha')->hasTag('form.type'));
    }
    
    public function testTinymce ()
    {
        $container = new ContainerBuilder();
        $loader = new NeutronFormExtension();
        $loader->load(array(array(
            'tinymce' => array(
                'tiny_mce_path_js' => 'xxx',
            )
        )), $container);
    
        $this->assertTrue($container->hasDefinition('neutron_form.form.type.tinymce'));
        $this->assertTrue($container->getDefinition('neutron_form.form.type.tinymce')->hasTag('form.type'));
    }
    
    public function testPlupload ()
    {
        $container = new ContainerBuilder();
        $loader = new NeutronFormExtension();
        $loader->load(array(array(
            'plupload' => array(
                'temporary_dir' => 'folder/',
            )
        )), $container);
    
        $this->assertTrue($container->getDefinition('neutron_form.form.type.image_upload')
            ->hasTag('form.type'));
        
        $this->assertTrue($container->getDefinition('neutron_form.form.type.file_upload')
            ->hasTag('form.type'));
        
        $this->assertTrue($container->getDefinition('neutron_form.form.type.multi_image_upload_collection')
            ->hasTag('form.type'));
        
        $this->assertTrue($container->getDefinition('neutron_form.form.type.multi_image_upload')
            ->hasTag('form.type'));
        
        $this->assertTrue($container->getDefinition('neutron_form.form.type.multi_file_upload_collection')
            ->hasTag('form.type'));
        
        $this->assertTrue($container->getDefinition('neutron_form.form.type.multi_file_upload')
            ->hasTag('form.type'));
        
        $this->assertTrue($container->getDefinition('neutron_form.doctrine.orm.event_subscriber.image_upload')
            ->hasTag('doctrine.event_subscriber'));
        
        $this->assertTrue($container->getDefinition('neutron_form.doctrine.orm.event_subscriber.file_upload')
            ->hasTag('doctrine.event_subscriber'));
    }
}