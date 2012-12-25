<?php
namespace Neutron\FormBundle\Tests\Manager;

use Symfony\Component\Filesystem\Filesystem;

use Neutron\FormBundle\Manager\ImageManager;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use org\bovigo\vfs\vfsStream;

class ImageManagerTest extends BaseTestCase
{
    private $root;
    
    public function setUp()
    {
        $this->root = vfsStream::setup('application');
    }
    
    public function testDirectories()
    {
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($this->getMock('Symfony\Component\Filesystem\Filesystem'));
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
        
        $this->assertSame('root', $manager->getRootDir());
        $this->assertSame('root/../web/temp', $manager->getTempDir());
        $this->assertSame('root/../web/temp/original', $manager->getTempOriginalDir());
        $this->assertSame('root/../web', $manager->getWebDir());
    }
    
    public function testCreateTemporaryDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('mkdir')
        ;
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
        
        $manager->createTemporaryDirectory();

    }
    
    public function testMakeImageCopy()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('copy')
        ;
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');

        $manager->makeImageCopy('test.jpg');

    }
    
    public function testGetPathOfTempOriginalImage()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');

        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');

        $this->assertSame('root/../web/temp/original/test.jpg', $manager->getPathOfTempOriginalImage('test.jpg'));

    }
    
    public function testGetPathOfTempImage()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');

        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');

        $this->assertSame('root/../web/temp/test.jpg', $manager->getPathOfTempImage('test.jpg'));

    }
    
    public function testGetHashOfTempImage()
    {
        vfsStream::newFile('web/temp/test.jpg')->at($this->root);
        
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');

        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir(vfsStream::url('application/root'));
        $manager->setTempDir('temp');

        $this->assertSame(md5_file(vfsStream::url('application/web/temp/test.jpg')), $manager->getHashOfTempImage('test.jpg'));

    }
    
    public function testCopyImagesToTemporaryDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->exactly(2))
            ->method('copy')
        ;
    
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('imagesExist')
            ->will($this->returnValue(true))
        ;
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfOriginalImage')
        ;
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryOriginalImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryImage')
        ;
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $manager->copyImagesToTemporaryDirectory($imageMock);
    
    }
    
    public function testCopyImagesToTemporaryDirectoryInvalid()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('imagesExist')
            ->will($this->returnValue(false))
        ;

        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $this->setExpectedException('Neutron\FormBundle\Exception\ImagesNotFoundException');
        $manager->copyImagesToTemporaryDirectory($imageMock);
    
    }
    
    public function testCopyImagesToPermenentDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->exactly(2))
            ->method('copy')
        ;
    
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('tempImagesExist')
            ->will($this->returnValue(true))
        ;
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfOriginalImage')
        ;
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryOriginalImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryImage')
        ;
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $manager->copyImagesToPermenentDirectory($imageMock);
    
    }
    
    public function testCopyImagesToPermenentDirectoryInvalid()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
    
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('tempImagesExist')
            ->will($this->returnValue(false))
        ;

        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $this->setExpectedException('Neutron\FormBundle\Exception\TempImagesNotFoundException');
        $manager->copyImagesToPermenentDirectory($imageMock);
    
    }
    
    public function testRemoveImagesFromTemporaryDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryOriginalImage')
        ;
        
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');

        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $manager->removeImagesFromTemporaryDirectory($imageMock);
    
    }
    
    public function testRemoveImagesFromPermenentDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfOriginalImage')
        ;
        
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');

        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $manager->removeImagesFromPermenentDirectory($imageMock);
    
    }
    
    public function testRemoveUnusedImages()
    {
        $web = vfsStream::newDirectory('web')->at($this->root);
        $temp = vfsStream::newDirectory('temp')->at($web);
        $tempOriginal = vfsStream::newDirectory('original')->at($temp);
       
        vfsStream::newFile('test.jpg')->at($temp);
        vfsStream::newFile('test.jpg')->at($tempOriginal);

        sleep(2);
        
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->exactly(2))
            ->method('remove')
        ;
        
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir(vfsStream::url('application/root'));
        $manager->setTempDir('temp');
     
        
        $manager->removeUnusedImages(1);
        
    }
    
    public function testRemoveAllImages()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $imageInfoMock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfTemporaryOriginalImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfImage')
        ;
        
        $imageInfoMock
            ->expects($this->exactly(1))
            ->method('getPathOfOriginalImage')
        ;
        
        $imageMock = $this->getMock('Neutron\FormBundle\Model\ImageInterface');
        
        $manager = new ImageManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setImageInfo($imageInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
        
        $manager->removeAllImages($imageMock);
    }
    
    
}
