<?php
namespace Neutron\FormBundle\Tests\Command;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Console\Tester\CommandTester;

use Neutron\FormBundle\Command\RemoveUnusedFilesCommand;

use Symfony\Component\Console\Application;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class RemoveUnusedFilesCommandTest extends BaseTestCase
{
    public function testExecute()
    {

        $application = new Application();
        $command = new RemoveUnusedFilesCommand();
        $command->setContainer($this->createContainerMock());
        $application->add($command);
    
        $command = $application->find('neutron:form:remove-unused-files');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
   
    }
    
    protected function createContainerMock()
    {
        $mockImageManager = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $mockImageManager->expects($this->once())->method('removeUnusedImages')->with(7200);
        
        $container = new ContainerBuilder();
        $container->set('neutron_form.manager.image_manager', $mockImageManager);
    
        return $container;
    }
}