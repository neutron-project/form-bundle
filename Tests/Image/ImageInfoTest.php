<?php
namespace Neutron\FormBundle\Tests\Image;

use Neutron\FormBundle\Tests\Fixture\Entity\Image;

use Neutron\FormBundle\Image\ImageInfo;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use org\bovigo\vfs\vfsStream;

class ImageInfoTest extends BaseTestCase
{
    private $root;
    
    public function setUp()
    {
        $this->root = vfsStream::setup('root');
    }
    
    public function testInvalidImage()
    {
        $this->setExpectedException('Neutron\FormBundle\Exception\EmptyImageException');
        $image = new Image();
        $imageInfo = new ImageInfo();
        $imageInfo->setImage(new Image());
        $imageInfo->getImage();
    }
    
    public function testGetPathImageUploadDir()
    {
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getWebDir')
            ->will($this->returnValue('/web'))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertSame('/web/media/images/main', $imageInfo->getPathImageUploadDir());
    }
    
    public function testGetPathOfTemporaryOriginalImage()
    {
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getTempDir')
            ->will($this->returnValue('/temp'))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertSame('/temp/original/test.jpg', $imageInfo->getPathOfTemporaryOriginalImage());
    }
    
    public function testGetPathOfOriginalImage()
    {
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getWebDir')
            ->will($this->returnValue('/web'))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertSame('/web/media/images/main/original/test.jpg', $imageInfo->getPathOfOriginalImage());
    }
    
    public function testGetPathOfTemporaryImage()
    {
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getTempDir')
            ->will($this->returnValue('/temp'))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertSame('/temp/test.jpg', $imageInfo->getPathOfTemporaryImage());
    }
    
    public function testGetPathOfImage()
    {
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getWebDir')
            ->will($this->returnValue('/web'))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertSame('/web/media/images/main/test.jpg', $imageInfo->getPathOfImage());
    }
    
    public function testGetTemporaryImageHash()
    {
        vfsStream::newFile('temp/test.jpg')->at($this->root);

        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getTempDir')
            ->will($this->returnValue(vfsStream::url('root/temp')))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertSame(md5_file(vfsStream::url('root/temp/test.jpg')), $imageInfo->getTemporaryImageHash());
    }
    
    public function testGetTemporaryImageHashInvalid()
    {
        $this->setExpectedException('Neutron\FormBundle\Exception\ImageNotFoundException');
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->once())
            ->method('getTempDir')
            ->will($this->returnValue('invalid.jpg'))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $imageInfo->getTemporaryImageHash();
    }
    
    public function testImageExist()
    {

        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true))
        ;
        
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->exactly(2))
            ->method('getWebDir')
            ->will($this->returnValue('web'))
        ;
        
        $imageManagerMock
            ->expects($this->once())
            ->method('getFileSystem')
            ->will($this->returnValue($filesystemMock))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertTrue($imageInfo->imagesExist());
    }
    
    public function testTempImageExist()
    {

        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true))
        ;
        
        $imageManagerMock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        $imageManagerMock
            ->expects($this->exactly(2))
            ->method('getTempDir')
            ->will($this->returnValue('web'))
        ;
        
        $imageManagerMock
            ->expects($this->once())
            ->method('getFileSystem')
            ->will($this->returnValue($filesystemMock))
        ;

        $image = new Image();
        $image->setName('test.jpg');
        
        $imageInfo = new ImageInfo();
        $imageInfo->setManager($imageManagerMock);
        $imageInfo->setImage($image);
        
        $this->assertTrue($imageInfo->tempImagesExist());
    }
    
    
}