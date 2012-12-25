<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Neutron\FormBundle\Form\Type\ColorPickerType;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

class ColorPickerTypeTest extends TypeTestCase
{

    public function testConfig()
    {

        $configs = array();
        
        $form = $this->factory->create('neutron_colorpicker', null, array(
            'configs' => $configs,
        ));

        $view = $form->createView();

        $this->assertEquals(array(), $view->vars['configs']);
    }

    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(new ColorPickerType())
			)
    	);
    }

}