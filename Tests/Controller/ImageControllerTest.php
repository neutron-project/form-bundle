<?php
namespace Neutron\FormBundle\Tests\Controller;

use Imagine\Exception\OutOfBoundsException;

use Imagine\Exception\InvalidArgumentException;

use Neutron\FormBundle\Controller\ImageController;

use Symfony\Component\DependencyInjection\Container;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class ImageControllerTest extends BaseTestCase
{    
    
    public function testInvalidRequest()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerInvalidRequest());
    
        $this->setExpectedException('RuntimeException');
        $controller->uploadAction();
    }   

    public function testUploadAction()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainer());
        
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->uploadAction());
    }
    
    public function testInvalidConfigs()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerInvalidConfigs());
    
        $this->setExpectedException('InvalidArgumentException');
        $controller->uploadAction();
    }
    
    public function testInvalidImage()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerInvalidImage());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->uploadAction());
    }
    
    public function testImageOversizeWidth()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerNormalizeImage(1200, 700, 3, 2));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->uploadAction());
    }
    
    public function testImageOversizeHeigth()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerNormalizeImage(700, 1200, 3, 4));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->uploadAction());
    }
    
    public function testCropAction()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerCropAction());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $controller->cropAction());
    }
    
    public function testInvalidAjaxRequest()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerInvalidAjaxRequest());
        
        $this->setExpectedException('InvalidArgumentException');
        $controller->validateRequest();
    }
    
    public function testCropActionWithInvalidImage()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerCropActionWithInvalidImagePath());

        $response = $controller->cropAction();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertRegExp('/exception.image.open/', $response->getContent());
    }
    
    public function testCropActionOutOfBounds()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerCropActionOutOfBounds());

        $response = $controller->cropAction();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertRegExp('/exception.image.out_of_bounds/', $response->getContent());
    }
    
    public function testCropActioInvalidArguments()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerCropActionInvalidArguments());

        $response = $controller->cropAction();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertRegExp('/exception.image.crop/', $response->getContent());
    }
    
    public function testRotateAction()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerRotateAction());

        $response = $controller->rotateAction();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertRegExp('/"success":true/', $response->getContent());
    }
    
    public function testResetAction()
    {
        $controller = new ImageController();
        $controller->setContainer($this->getContainerResetAction());

        $response = $controller->resetAction();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertRegExp('/"success":true/', $response->getContent());
    }
    
    private function getContainerInvalidRequest()
    {
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    
        $requestMock
            ->expects($this->once())
            ->method('isMethod')
            ->with('POST')
            ->will($this->returnValue(false))
        ;
         
        $requestMock->files = null;
         
        $container = new Container();
        $container->set('request', $requestMock);
         
        return $container;
    }
    
    private function getContainerInvalidAjaxRequest()
    {
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    
        $requestMock
            ->expects($this->once())
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(false))
        ;
 
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
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getTempOriginalDir')
            ->will($this->returnValue('temp'))
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempOriginalImage')
            ->will($this->returnValue('image_path'))
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('makeImageCopy')
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('getHashOfTempImage')
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
                'extensions' => 'jpg,gif',
                'minWidth' => 300,
                'minHeight' => 100 
            )))
        ;
            
        $boxMock = $this->getMock('Imagine\Image\BoxInterface');
        $boxMock
            ->expects($this->exactly(4))
            ->method('getWidth')
            ->will($this->returnValue(350))
        ;
        $boxMock
            ->expects($this->exactly(3))
            ->method('getHeight')
            ->will($this->returnValue(250))
        ;
            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');
        $imageMock
            ->expects($this->exactly(1))
            ->method('getSize')
            ->will($this->returnValue($boxMock))
        ;
        
            
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->with('image_path')
            ->will($this->returnValue($imageMock))
        ;
            
        $validatorMock = $this->getMock('Symfony\Component\Validator\ValidatorInterface');
        $validatorMock
            ->expects($this->once())
            ->method('validateValue')
            ->will($this->returnValue(array()))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->set('imagine', $imagineMock);
        $container->set('validator', $validatorMock);
        $container->setParameter('neutron_form.plupload.configs', array(
            'normalize_width' => 1000,
            'normalize_height' => 1000        
        ));
        
        return $container;
    }
    
    private function getContainerCropAction()
    {    
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $requestMock
            ->expects($this->at(0))
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(true))
        ;
        
        $requestMock
            ->expects($this->at(1))
            ->method('get')
            ->with('name')
            ->will($this->returnValue('image.jpg'))
        ;
         
        $requestMock
            ->expects($this->at(2))
            ->method('get')
            ->with('x')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(3))
            ->method('get')
            ->with('y')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(4))
            ->method('get')
            ->with('w')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(5))
            ->method('get')
            ->with('h')
            ->will($this->returnValue(20))
        ;

        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempImage')
            ->will($this->returnValue('temp_image.jpg'))
        ;

        $managerMock
            ->expects($this->exactly(1))
            ->method('getHashOfTempImage')
            ->will($this->returnValue('hash'))
        ;

        
        $boxMock = $this->getMock('Imagine\Image\BoxInterface');
            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');
        $imageMock
            ->expects($this->exactly(1))
            ->method('crop')
            ->will($this->returnSelf())
        ;
        $imageMock
            ->expects($this->exactly(1))
            ->method('save')
            ->will($this->returnSelf())
        ;
        
            
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->with('temp_image.jpg')
            ->will($this->returnValue($imageMock))
        ;

        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->exactly(0))
            ->method('trans')
            ->will($this->returnValue(array('key' => 'translated_message')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('imagine', $imagineMock);
        $container->set('translator', $translatorMock);
        
        return $container;
    }
    
    private function getContainerRotateAction()
    {    
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $requestMock
            ->expects($this->at(0))
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(true))
        ;
        
        $requestMock
            ->expects($this->at(1))
            ->method('get')
            ->with('name')
            ->will($this->returnValue('image.jpg'))
        ;

        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempImage')
            ->will($this->returnValue('temp_image.jpg'))
        ;

        $managerMock
            ->expects($this->exactly(1))
            ->method('getHashOfTempImage')
            ->will($this->returnValue('hash'))
        ;

        
        $boxMock = $this->getMock('Imagine\Image\BoxInterface');
            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');
        $imageMock
            ->expects($this->exactly(1))
            ->method('rotate')
            ->will($this->returnSelf())
        ;
        $imageMock
            ->expects($this->exactly(1))
            ->method('save')
            ->will($this->returnSelf())
        ;
        
            
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->with('temp_image.jpg')
            ->will($this->returnValue($imageMock))
        ;

        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->exactly(0))
            ->method('trans')
            ->will($this->returnValue(array('key' => 'translated_message')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('imagine', $imagineMock);
        $container->set('translator', $translatorMock);
        
        return $container;
    }
    
    private function getContainerResetAction()
    {    
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $requestMock
            ->expects($this->at(0))
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(true))
        ;
        
        $requestMock
            ->expects($this->at(1))
            ->method('get')
            ->with('name')
            ->will($this->returnValue('image.jpg'))
        ;

        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('makeImageCopy')
        ;

        $managerMock
            ->expects($this->exactly(1))
            ->method('getHashOfTempImage')
            ->will($this->returnValue('hash'))
        ;

        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->exactly(0))
            ->method('trans')
            ->will($this->returnValue(array('key' => 'translated_message')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('translator', $translatorMock);
        
        return $container;
    }
    
    private function getContainerCropActionWithInvalidImagePath()
    {    
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $requestMock
            ->expects($this->at(0))
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(true))
        ;
        
        $requestMock
            ->expects($this->at(1))
            ->method('get')
            ->with('name')
            ->will($this->returnValue('image.jpg'))
        ;
         
        $requestMock
            ->expects($this->at(2))
            ->method('get')
            ->with('x')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(3))
            ->method('get')
            ->with('y')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(4))
            ->method('get')
            ->with('w')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(5))
            ->method('get')
            ->with('h')
            ->will($this->returnValue(20))
        ;

        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempImage')
            ->will($this->returnValue(null))
        ;
            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');

                   
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->will($this->throwException(new InvalidArgumentException('open invalid')))
        ;

        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->exactly(1))
            ->method('trans')
            ->will($this->returnValue(array('key' => 'exception.image.open')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('imagine', $imagineMock);
        $container->set('translator', $translatorMock);
        
        return $container;
    }
    
    
    private function getContainerCropActionOutOfBounds()
    {    
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $requestMock
            ->expects($this->at(0))
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(true))
        ;
        
        $requestMock
            ->expects($this->at(1))
            ->method('get')
            ->with('name')
            ->will($this->returnValue('image.jpg'))
        ;
         
        $requestMock
            ->expects($this->at(2))
            ->method('get')
            ->with('x')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(3))
            ->method('get')
            ->with('y')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(4))
            ->method('get')
            ->with('w')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(5))
            ->method('get')
            ->with('h')
            ->will($this->returnValue(20))
        ;

        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempImage')
            ->will($this->returnValue(null))
        ;
            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');
        
        $imageMock
            ->expects($this->exactly(1))
            ->method('crop')
            ->will($this->throwException(new OutOfBoundsException('out of bounds')))
        ;
         
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->will($this->returnValue($imageMock))
        ;
        
        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->exactly(1))
            ->method('trans')
            ->will($this->returnValue(array('key' => 'exception.image.out_of_bounds')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('imagine', $imagineMock);
        $container->set('translator', $translatorMock);
        
        return $container;
    }
    
    private function getContainerCropActionInvalidArguments()
    {    
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $requestMock
            ->expects($this->at(0))
            ->method('isXmlHttpRequest')
            ->will($this->returnValue(true))
        ;
        
        $requestMock
            ->expects($this->at(1))
            ->method('get')
            ->with('name')
            ->will($this->returnValue('image.jpg'))
        ;
         
        $requestMock
            ->expects($this->at(2))
            ->method('get')
            ->with('x')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(3))
            ->method('get')
            ->with('y')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(4))
            ->method('get')
            ->with('w')
            ->will($this->returnValue(20))
        ;
        
        $requestMock
            ->expects($this->at(5))
            ->method('get')
            ->with('h')
            ->will($this->returnValue(20))
        ;

        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempImage')
            ->will($this->returnValue(null))
        ;
            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');
        
        $imageMock
            ->expects($this->exactly(1))
            ->method('crop')
            ->will($this->throwException(new InvalidArgumentException('invalid coords')))
        ;
         
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->will($this->returnValue($imageMock))
        ;
        
        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->exactly(1))
            ->method('trans')
            ->will($this->returnValue(array('key' => 'exception.image.crop')))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('imagine', $imagineMock);
        $container->set('translator', $translatorMock);
        
        return $container;
    }
    
    private function getContainerNormalizeImage($width, $height, $widthCall, $heightCall)
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
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getTempOriginalDir')
            ->will($this->returnValue('temp'))
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('getPathOfTempOriginalImage')
            ->will($this->returnValue('image_path'))
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('makeImageCopy')
        ;
        $managerMock
            ->expects($this->exactly(1))
            ->method('getHashOfTempImage')
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
                'extensions' => 'jpg,gif',
                'minWidth' => 300,
                'minHeight' => 100 
            )))
        ;
            
        $boxMock = $this->getMock('Imagine\Image\BoxInterface');
        $boxMock
            ->expects($this->exactly($widthCall))
            ->method('getWidth')
            ->will($this->returnValue($width))
        ;
        $boxMock
            ->expects($this->exactly($heightCall))
            ->method('getHeight')
            ->will($this->returnValue($height))
        ;

            
        $imageMock = $this->getMock('Imagine\Image\ImageInterface');
        $imageMock
            ->expects($this->exactly(1))
            ->method('getSize')
            ->will($this->returnValue($boxMock))
        ;
        $imageMock
            ->expects($this->exactly(1))
            ->method('resize')
            ->will($this->returnSelf())
        ;
        $imageMock
            ->expects($this->exactly(1))
            ->method('save')
            ->will($this->returnSelf())
        ;
        
            
        $imagineMock = $this->getMock('Imagine\Image\ImagineInterface');
        $imagineMock
            ->expects($this->exactly(1))
            ->method('open')
            ->with('image_path')
            ->will($this->returnValue($imageMock))
        ;
            
        $validatorMock = $this->getMock('Symfony\Component\Validator\ValidatorInterface');
        $validatorMock
            ->expects($this->once())
            ->method('validateValue')
            ->will($this->returnValue(array()))
        ;
        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->set('imagine', $imagineMock);
        $container->set('validator', $validatorMock);
        $container->setParameter('neutron_form.plupload.configs', array(
            'normalize_width' => 1000,
            'normalize_height' => 1000        
        ));
        
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
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');

        
        $sessionMock = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionMock
            ->expects($this->exactly(1))
            ->method('has')
            ->will($this->returnValue(false))
        ;
        

        
        $container = new Container();
        $container->set('request', $requestMock);
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->setParameter('neutron_form.plupload.configs', array());
        
        return $container;
    }
    
    private function getContainerInvalidImage()
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
         
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');

        
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
                'minWidth' => 300,
                'minHeight' => 100,
                'maxSize' => '2M',
                'extensions' => 'jpg,gif'        
            )))
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
        $container->set('neutron_form.manager.image_manager', $managerMock);
        $container->set('session', $sessionMock);
        $container->set('validator', $validatorMock);
        $container->set('translator', $translatorMock);
        $container->setParameter('neutron_form.plupload.configs', array());
        
        return $container;
    }
}