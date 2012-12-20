<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Neutron\FormBundle\Form\Type\RatingType;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class RatingTypeTest extends TypeTestCase
{

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('neutron_rating', null, array('configs' => array('path' => 'path_to_images')));
        $view = $form->createView();
        $configs = $view->vars['configs'];
        $this->assertSame(array(
            'path' => 'path_to_images'
        ), $configs);
    }
    
    public function testInvalidConfigs()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $form = $this->factory->create('neutron_rating');
    }

    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(new RatingType())
			)
    	);
    }
}