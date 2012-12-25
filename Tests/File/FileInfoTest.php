<?php
namespace Neutron\FormBundle\Test\File;

use Neutron\FormBundle\File\FileInfo;

use Neutron\FormBundle\Tests\Fixture\Entity\File;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use org\bovigo\vfs\vfsStream;

class FileInfoTest extends BaseTestCase
{
    private $root;
    
    public function setUp()
    {
        $this->root = vfsStream::setup('root');
    }
    
    public function testInvalidFile()
    {
        $this->setExpectedException('Neutron\FormBundle\Exception\FileEmptyException');
        $file = new File();
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->getFile();
    }
    
    public function testGetPathOfFileUploadDir()
    {
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->once())
            ->method('getWebDir')
            ->will($this->returnValue('/web'))
        ;
        
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
        
        $this->assertSame('/web/media/files/main', $fileInfo->getPathFileUploadDir());
    }
    
    public function testGetPathOfTemporaryFile()
    {
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->once())
            ->method('getTempDir')
            ->will($this->returnValue('/temp'))
        ;
        
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
        
        $this->assertSame('/temp/test.txt', $fileInfo->getPathOfTemporaryFile());
    }
    
    public function testGetPathOfFile()
    {
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->once())
            ->method('getWebDir')
            ->will($this->returnValue('/web'))
        ;
        
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
        
        $this->assertSame('/web/media/files/main/test.txt', $fileInfo->getPathOfFile());
    }
    
    public function testGetTemporaryFileHash()
    {
        vfsStream::newFile('temp/test.txt')->at($this->root);
    
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true))
        ;
        
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->exactly(2))
            ->method('getTempDir')
            ->will($this->returnValue(vfsStream::url('root/temp')))
        ;
        
        $managerMock
            ->expects($this->exactly(1))
            ->method('getFileSystem')
            ->will($this->returnValue($filesystemMock))
        ;
    
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
    
        $this->assertSame(md5_file(vfsStream::url('root/temp/test.txt')), $fileInfo->getTemporaryFileHash());
    }
    
    public function testGetTemporaryFileHashInvalid()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false))
        ;
        
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->exactly(1))
            ->method('getTempDir')
            ->will($this->returnValue('/temp'))
        ;
        
        $managerMock
            ->expects($this->once())
            ->method('getFileSystem')
            ->will($this->returnValue($filesystemMock))
        ;
    
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
    
        $this->setExpectedException('Neutron\FormBundle\Exception\TempFileNotFoundException');
        $fileInfo->getTemporaryFileHash();
    }
    
    public function testFileExists()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true))
        ;
        
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->once())
            ->method('getFileSystem')
            ->will($this->returnValue($filesystemMock))
        ;
        
        $managerMock
            ->expects($this->once())
            ->method('getWebDir')
            ->will($this->returnValue('/web'))
        ;
    
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
    
        $this->assertTrue($fileInfo->fileExists());
    }
    
    public function testTempFileExists()
    {
        $filesystemMock = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        
        $filesystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true))
        ;
        
        $managerMock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
        $managerMock
            ->expects($this->once())
            ->method('getFileSystem')
            ->will($this->returnValue($filesystemMock))
        ;
        
        $managerMock
            ->expects($this->once())
            ->method('getTempDir')
            ->will($this->returnValue('/temp'))
        ;
    
        $file = new File();
        $file->setName('test.txt');
        $fileInfo = new FileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setManager($managerMock);
    
        $this->assertTrue($fileInfo->tempFileExists());
    }
}