<?php
namespace Neutron\FormBundle\Tests\Controller;

use Symfony\Component\DependencyInjection\Container;

use Neutron\FormBundle\Controller\FileController;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class FileControllerTest extends BaseTestCase
{    
    
    public function testInvalidRequest()
    {
        $controller = new FileController();
        $controller->setContainer($this->getContainerInvalidRequest());
        
        $this->setExpectedException('RuntimeException');
        $controller->uploadAction();
    }
    
    public function testDefault()
    {
        $controller = new FileController();
        $controller->setContainer($this->getContainer());
        
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->uploadAction());
    }
    
    public function testInvalidConfigs()
    {
        $controller = new FileController();
        $controller->setContainer($this->getContainerInvalidConfigs());
        
        $this->setExpectedException('InvalidArgumentException');
        $controller->uploadAction();
    }
    
    public function testInvalidFile()
    {
        $controller = new FileController();
        $controller->setContainer($this->getContainerInvalidFile());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->uploadAction());
    }
    
    private function getContainerInvalidRequest()
    {    
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
         $requestMock
            ->expects($this->at(0))
            ->method('isMethod')
            ->with('POST')
            ->will($this->returnValue(false))
        ;
         
        $requestMock->files = null;
         
        $container = new Container();
        $container->set('request', $requestMock);     
           
        return $container;
    }
    
    private function getContainer()
    {    
        
        $uploadedFileMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $uploadedFileMock
            ->expects($this->once())
            ->method('move')
        ;
        
        $uploadedFileMock
            ->expects($this->once())
            ->method('guessExtension')
            ->will($this->returnValue('txt'))
        ;
        
        $fileBagMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\FileBag')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $fileBagMock
            ->expects($this->once())
            ->method('get')
            ->with('file')
            ->will($this->returnValue($uploadedFileMock))
        ;
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
         $requestMock
            ->expects($this->at(0))
            ->method('isMethod')
            ->with('POST')
            ->will($this->returnValue(true))
        ;
         
        $requestMock->files = $fileBagMock;
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getTempDir')
            ->will($this->returnValue('temp'))
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('getHashOfTempFile')
            ->will($this->returnValue('hash'))
        ;
        
        $sessionMock = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionMock
            ->expects($this->exactly(1))
            ->method('has')
            ->will($this->returnValue(true))
        ;
        
        $sessionMock
            ->expects($this->exactly(1))
            ->method('get')
            ->will($this->returnValue(array(
                'maxSize' => '2M',
                'extensions' => 'pdf,txt'   
            )))
        ;
            
        $validatorMock = $this->getMock('Symfony\Component\Validator\ValidatorInterface');
        $validatorMock
            ->expects($this->once())
            ->method('validateValue')
            ->will($this->returnValue(array()))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.file_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->set('validator', $validatorMock);
        $container->setParameter('neutron_form.plupload.configs', array());
        
        return $container;
    }
    
    private function getContainerInvalidConfigs()
    {    
        
        $uploadedFileMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $uploadedFileMock
            ->expects($this->once())
            ->method('guessExtension')
            ->will($this->returnValue('txt'))
        ;
        
        $fileBagMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\FileBag')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $fileBagMock
            ->expects($this->once())
            ->method('get')
            ->with('file')
            ->will($this->returnValue($uploadedFileMock))
        ;
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
         $requestMock
            ->expects($this->exactly(1))
            ->method('isMethod')
            ->with('POST')
            ->will($this->returnValue(true))
        ;
         
        $requestMock->files = $fileBagMock;
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');

        
        $sessionMock = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionMock
            ->expects($this->exactly(1))
            ->method('has')
            ->will($this->returnValue(false))
        ;
        

        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.file_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->setParameter('neutron_form.plupload.configs', array());
        
        return $container;
    }
    
    private function getContainerInvalidFile()
    {    
        
        $uploadedFileMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $uploadedFileMock
            ->expects($this->once())
            ->method('guessExtension')
            ->will($this->returnValue('txt'))
        ;
        
        $fileBagMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\FileBag')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $fileBagMock
            ->expects($this->once())
            ->method('get')
            ->with('file')
            ->will($this->returnValue($uploadedFileMock))
        ;
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
         $requestMock
            ->expects($this->exactly(1))
            ->method('isMethod')
            ->with('POST')
            ->will($this->returnValue(true))
        ;
         
        $requestMock->files = $fileBagMock;
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');

        
        $sessionMock = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionMock
            ->expects($this->exactly(1))
            ->method('has')
            ->will($this->returnValue(true))
        ;
        
        $constraintValidationMock = $this->getMockBuilder('Symfony\Component\Validator\ConstraintViolation')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $constraintValidationMock
            ->expects($this->once())
            ->method('getMessageTemplate')
            ->will($this->returnValue('message_template'))
        ;
        
        $constraintValidationMock
            ->expects($this->once())
            ->method('getMessageParameters')
            ->will($this->returnValue(array()))
        ;
        
        $validatorMock = $this->getMock('Symfony\Component\Validator\ValidatorInterface');
        $validatorMock
            ->expects($this->once())
            ->method('validateValue')
            ->will($this->returnValue(array(
                $constraintValidationMock
            )))
        ;
        
        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->once())
            ->method('trans')
            ->will($this->returnValue(array('key' => 'translated_message')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.file_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->set('validator', $validatorMock);
        $container->set('translator', $translatorMock);
        $container->setParameter('neutron_form.plupload.configs', array());
        
        return $container;
    }
}