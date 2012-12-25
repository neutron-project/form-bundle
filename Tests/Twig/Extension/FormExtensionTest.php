<?php
namespace Neutron\FormBundle\Tests\Twig\Extension;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use Symfony\Component\DependencyInjection\Scope;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Neutron\FormBundle\Twig\Extension\FormExtension;

class FormExtensionTest extends BaseTestCase
{

    public function setUp()
    {
        if (!class_exists('Twig_Environment')) {
            $this->markTestSkipped('Twig is not available');
        }
    }

    public function testImage()
    {
        $result = $this->getTemplate("{{ neutron_image(image,filter,options) }}")
            ->render(array(
                'image' => $this->createImageMock(),
                'filter' => 'some_filter',
                'options' => array())
            );
    }
    
    public function testFileSize()
    {
        $result = $this->getTemplate("{{ neutron_filesize(bytes) }}", 0)
            ->render(array('bytes' => 2000000));
    }

    private function getTemplate($template, $render = 1)
    {
        $loader = new \Twig_Loader_Array(array('index' => $template));
        $twig = new \Twig_Environment($loader, array('debug' => true, 'cache' => false));
        $twig->addExtension(new FormExtension($this->createContainerMock($render)));

        return $twig->loadTemplate('index');
    }

    protected function createContainerMock($render)
    {
        $templatingMock =
            $this
                ->getMockBuilder('Symfony\Bundle\TwigBundle\TwigEngine')
                 ->disableOriginalConstructor()->getMock()
            ;

        $templatingMock
            ->expects($this->exactly($render))
            ->method('render')
        ;

        $container = new ContainerBuilder();

        $container->set('templating', $templatingMock);
        $container->addScope(new Scope('request'));
        $container->register('request', 'Symfony\Component\HttpFoundation\Request')
            ->setScope('request');
        $container->enterScope('request');

        return $container;
    }

    protected function createImageMock()
    {
    	$image = $this->getMock('Neutron\FormBundle\Model\ImageInterface');

    	$image
        	->expects($this->any())
        	->method('getName')
        	->will($this->returnValue('image.jpg'));

    	$image
        	->expects($this->any())
        	->method('getUploadDir')
        	->will($this->returnValue('/image'));

    	return $image;
    }
}