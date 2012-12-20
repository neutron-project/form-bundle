<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Neutron\FormBundle\Form\Type\RecaptchaType;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class RecaptchaTypeTest extends TypeTestCase
{

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('neutron_recaptcha');
        $view = $form->createView();
        $configs = $view->vars['configs'];
        $this->assertSame(array(), $configs);
    }
    
    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(new RecaptchaType(array()))
			)
    	);
    }
}