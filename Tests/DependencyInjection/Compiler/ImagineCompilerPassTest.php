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
}
