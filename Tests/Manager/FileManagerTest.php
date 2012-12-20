<?php
namespace Neutron\FormBundle\Tests\Manager;

use Neutron\FormBundle\Tests\Fixture\Entity\File;

use Neutron\FormBundle\Manager\FileManager;

use Symfony\Component\Filesystem\Filesystem;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use org\bovigo\vfs\vfsStream;

class FileManagerTest extends BaseTestCase
{
    private $root;
    
    public function setUp()
    {
        $this->root = vfsStream::setup('application');
    }
    
    public function testDirectories()
    {
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\FileInfoInterface');
        
        $manager = new FileManager();
        $manager->setFilesystem($this->getMock('Symfony\Component\Filesystem\Filesystem'));
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir('root');

        $manager->setTempDir('temp');
        
        $this->assertSame('root', $manager->getRootDir());
        $this->assertSame('root/../web/temp', $manager->getTempDir());
        $this->assertSame('root/../web', $manager->getWebDir());
    }
    
    public function testCreateTemporaryDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
    
        $filesystemMock
            ->expects($this->once())
            ->method('mkdir')
        ;
    
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');
    
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFIleInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $manager->createTemporaryDirectory();
    
    }
    
    public function testGetPathOfTempImage()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
    
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');
    
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFIleInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
    
        $this->assertSame('root/../web/temp/test.txt', $manager->getPathOfTempFIle('test.txt'));
    
    }
    
    public function testGetHashOfTempImage()
    {
        vfsStream::newFile('web/temp/test.txt')->at($this->root);
    
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
    
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\FIleInfoInterface');
    
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir(vfsStream::url('application/root'));
        $manager->setTempDir('temp');
    
        $this->assertSame(md5_file(vfsStream::url('application/web/temp/test.txt')), $manager->getHashOfTempFile('test.txt'));
    }
    
    public function testCopyFileToPermenentDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->exactly(1))
            ->method('copy')
        ;
        
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');
        
        $fileInfoMock
            ->expects($this->once())
            ->method('getPathOfFile')
        ;
        
        $fileInfoMock
            ->expects($this->once())
            ->method('tempFileExists')
            ->will($this->returnValue(true))
        ;
        
        $fileInfoMock
            ->expects($this->once())
            ->method('getPathOfTemporaryFile')
        ;
        
        $file = new File();
        $file->setName('test.txt');
        
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
        
        $manager->copyFileToPermenentDirectory($file);
    }
    
    public function testCopyFileToPermenentDirectoryInvalid()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');

        
        $fileInfoMock
            ->expects($this->once())
            ->method('tempFileExists')
            ->will($this->returnValue(false))
        ;

        
        $file = new File();
        $file->setName('test.txt');
        
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');
        
        $this->setExpectedException('Neutron\FormBundle\Exception\FileNotFoundException');
        $manager->copyFileToPermenentDirectory($file);
    }
    
    public function testRemoveFileToTemporaryDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystemMock
            ->expects($this->exactly(1))
            ->method('remove')
        ;
        
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');
        
        $fileInfoMock
            ->expects($this->once())
            ->method('getPathOfTemporaryFile')
        ;

        
        $file = new File();
        $file->setName('test.txt');
        
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');

        $manager->removeFileFromTemporaryDirectory($file);
    }
    
    public function testRemoveFileToPermenentDirectory()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystemMock
            ->expects($this->exactly(1))
            ->method('remove')
        ;
        
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');
        
        $fileInfoMock
            ->expects($this->once())
            ->method('getPathOfFile')
        ;

        
        $file = new File();
        $file->setName('test.txt');
        
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');

        $manager->removeFileFromPermenentDirectory($file);
    }
    
    public function testRemoveAllFiles()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystemMock
            ->expects($this->exactly(2))
            ->method('remove')
        ;
        
        $fileInfoMock = $this->getMock('Neutron\FormBundle\File\fileInfoInterface');
        
        $fileInfoMock
            ->expects($this->once())
            ->method('getPathOfFile')
        ;
        
        $fileInfoMock
            ->expects($this->once())
            ->method('getPathOfTemporaryFile')
        ;

        
        $file = new File();
        $file->setName('test.txt');
        
        $manager = new FileManager();
        $manager->setFilesystem($filesystemMock);
        $manager->setFileInfo($fileInfoMock);
        $manager->setRootDir('root');
        $manager->setTempDir('temp');

        $manager->removeAllFiles($file);
    }
    
}
