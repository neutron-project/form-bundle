<?php
namespace Neutron\FormBundle\TestsDependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Neutron\FormBundle\DependencyInjection\Compiler\ImagineCompilerPass;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class ImagineCompilerPassTest extends BaseTestCase
{
    public function testProcessWithoutProviderDefinition()
    {
        $imaginePass = new ImagineCompilerPass();
    
        $this->assertNull(
            $imaginePass->process($this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder'))
        );
    }
    
    public function testProcessWithoutExtension()
    {
        $this->setExpectedException('\RuntimeException');
        $container = new ContainerBuilder();
        $container->setParameter('neutron_form.plupload.configs', 'some');
        $imaginePass = new ImagineCompilerPass();
        $imaginePass->process($container);
    }
    
    public function testProcessMissingExtension()
    {
        $this->setExpectedException('RuntimeException');
        
        $containerMock =
            $this
                ->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
                ->disableOriginalConstructor()
                ->getMock()
            ;
        
        $containerMock
            ->expects($this->once())
            ->method('hasParameter')
            ->with('neutron_form.plupload.configs')
            ->will($this->returnValue(true))
        ;
        
        $containerMock
            ->expects($this->once())
            ->method('hasExtension')
            ->with('avalanche_imagine')
            ->will($this->returnValue(false))
        ;
        
        $imaginePass = new ImagineCompilerPass();
        $imaginePass->process($containerMock);
    }
    
    public function testProcessWithExtension()
    {

        $definitionMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $definitionMock
            ->expects($this->once())
            ->method('addMethodCall')
        ;
        
        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $containerMock
            ->expects($this->once())
            ->method('hasParameter')
            ->with('neutron_form.plupload.configs')
            ->will($this->returnValue(true))
        ;
        
        $containerMock
            ->expects($this->once())
            ->method('getParameter')
            ->with('imagine.cache_prefix')
            ->will($this->returnValue('temp'))
        ;
        
        $containerMock
            ->expects($this->once())
            ->method('hasExtension')
            ->with('avalanche_imagine')
            ->will($this->returnValue(true))
        ;
        
        $containerMock
            ->expects($this->once())
            ->method('getDefinition')
            ->with('neutron_form.manager.image_manager')
            ->will($this->returnValue($definitionMock))
        ;
        
        $imaginePass = new ImagineCompilerPass();
        $imaginePass->process($containerMock);
    }
}
