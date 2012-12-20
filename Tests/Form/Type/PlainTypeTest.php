<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Neutron\FormBundle\Tests\Stub\Test;

use Neutron\FormBundle\Form\Type\PlainType;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class PlainTypeTest extends TypeTestCase
{

    public function testWithString()
    {
        $form = $this->factory->create('neutron_plain');
        $form->bind('test');
        $view = $form->createView();
        
        $this->assertSame('test', $view->vars['value']);
       
    }

    public function testWithArrayValue()
    {
        $form = $this->factory->create('neutron_plain');
        $form->bind(array('one', 'two'));
        $view = $form->createView();
        
        $this->assertSame('one, two', $view->vars['value']);
       
    }

    public function testWithEmptyValue()
    {
        $form = $this->factory->create('neutron_plain');
        $form->bind(null);
        $view = $form->createView();
        
        $this->assertSame('-----', $view->vars['value']);
       
    }

    public function testWithDateTimeValue()
    {
        $form = $this->factory->create('neutron_plain');
        $form->bind(new \DateTime('2012-12-21 13:14:00'));
        $view = $form->createView();
        
        $this->assertSame('December 21, 2012 1:14:00 PM', $view->vars['value']);
       
    }

    public function testWithObjectValue()
    {
        $form = $this->factory->create('neutron_plain');
        $form->bind(new \stdClass());
        $view = $form->createView();
        
        $this->assertSame('stdClass', $view->vars['value']);
       
    }
    
    public function testWithClassValue()
    {
        $form = $this->factory->create('neutron_plain');
        $form->bind(new Test());
        $view = $form->createView();
        
        $this->assertSame('test stub', $view->vars['value']);
       
    }
    
    private function getMockRequest()
    {
        $mock = $this->getMock('Symfony\Component\HttpFoundation\Request');
        
        $mock
            ->expects($this->any())
            ->method('getLocale')
            ->will($this->returnValue('en'))
        ;
        
        return $mock;
    }

    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(new PlainType($this->getMockRequest()))
			)
    	);
    }
}