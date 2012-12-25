<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Form\Type\ImageUploadType;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class ImageUploadTypeTest extends TypeTestCase
{

    public function testInvalidConfigs()
    {
        $this->setExpectedException('InvalidArgumentException');
    	$form = $this->factory->create('neutron_image_upload');
    }

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('neutron_image_upload', null, array(
            'data_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\Image',
            'configs' => array('minWidth' => 300, 'minHeight' => 100, 'extensions' => 'pdf')
        ));
        $form->setData($this->createMockImage());
        
        $view = $form->createView();
        $configs = $view->get('configs');
        $this->assertNotEmpty($configs);
    }

    protected function getExtensions()
    {
        $options = array(
            'runtimes' => 'html5',
            'max_upload_size' => '4M',
            'temporary_dir' => 'temp',
        );

    	return array(
			new TypeExtensionTest(
				array(
				    new ImageUploadType(
				        $this->createSessionMock(),
				        $this->createRouterMock(),
				        $this->createMockFormEventSubscriber(),
				        $options
				    )
				 )
			)
    	);
    }

    protected function createSessionMock()
    {
        $session =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                ->disableOriginalConstructor()
                ->getMock();

        return $session;
    }

    protected function createRouterMock()
    {

    	$router =
    	    $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
    	        ->disableOriginalConstructor()
    	        ->getMock();

    	$router
    	    ->expects($this->any())
    	    ->method('generate')
    	    ->will($this->returnValue('http://neutron.local/'));

    	return $router;
    }
    
    
    protected function createMockFormEventSubscriber()
    {
        $mock =
            $this->getMock('Symfony\Component\EventDispatcher\EventSubscriberInterface');

        $mock::staticExpects($this->any())
            ->method('getSubscribedEvents')
            ->will($this->returnValue(array()));

        return $mock;
    }
   
    
    
    protected function createMockImage()
    {
        $mock = $this->getMock('Neutron\FormBundle\Tests\Fixture\Entity\Image');;

        $mock
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1))
        ;
        
        $mock
            ->expects($this->exactly(2))
            ->method('isEnabled')
            ->will($this->returnValue(true))
        ;

        return $mock;
    }
}