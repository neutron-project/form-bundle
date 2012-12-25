<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Neutron\FormBundle\Form\Type\TinyMceType;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class TinyMceTypeTest extends TypeTestCase
{

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('neutron_tinymce');
        $view = $form->createView();
        $configs = $view->get('configs');
        $this->assertSame(array(
            'security' => array('ROLE_ADMIN'),
        ), $configs);
    }



    protected function createSecurityContextMockGuestSession()
    {
    	$token =
    	    $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

    	$token
        	->expects($this->once())
        	->method('getUser')
        	->will($this->returnValue('anon.'));

    	$securityContext =
    	$this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
        	->disableOriginalConstructor()
        	->getMock();

    	$securityContext
        	->expects($this->once())
        	->method('getToken')
        	->will($this->returnValue($token));

    	return $securityContext;
    }

    protected function createSecurityMock()
    {
        $security =
            $this->getMockBuilder('Neutron\FormBundle\Security\Handler\TinyMceSecurityHandler')
                ->disableOriginalConstructor()
                ->getMock();
        $security
            ->expects($this->once())
            ->method('authorize')
            ->with(array('ROLE_ADMIN'))
            ->will($this->returnValue(true));

        return $security;
    }

    protected function getExtensions()
    {
        return array(
            new TypeExtensionTest(
                array(
                    new TinyMceType(
                        $this->createSecurityMock(),
                		array('security' => array('ROLE_ADMIN'))
                    )
                )
            )
        );
    }
}