<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Manager;

use Neutron\FormBundle\Exception\FileNotFoundException;

use Neutron\FormBundle\Model\FileInterface;

use Neutron\FormBundle\File\FileInfo;

use Neutron\FormBundle\File\FileInfoInterface;

use Neutron\FormBundle\Exception\TempImagesNotFoundException;

use Neutron\FormBundle\Exception\ImagesNotFoundException;

use Neutron\FormBundle\Model\ImageInterface;

use Neutron\FormBundle\Image\ImageInfo;

use Symfony\Component\Filesystem\Filesystem;

/**
 * This class is responsible for file paths and directories.
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class FileManager implements FileManagerInterface
{
    /**
     * @var Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;
    
    /**
     * @var string
     */
    protected $rootDir;
    
    /**
     * @var string
     */
    protected $tempDir;
    
    /**
     * @var string
     */
    protected $cacheDir;
    

    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    

    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
 
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }
    

    public function getRootDir()
    {
        return $this->rootDir;
    }
    

    public function getWebDir()
    {
        return realpath($this->getRootDir() . '/../web');
    }
    

    public function setTempDir($tempDir)
    {
        $this->tempDir = $this->getWebDir() . DIRECTORY_SEPARATOR . $tempDir;
    }
    

    public function getTempDir()
    {
        return $this->tempDir;
    }
    
    
    public function getFileInfo(FileInterface $file)
    {
        return new FileInfo($file, $this);
    }
    
    public function createTemporaryDirectory()
    {
        $this->getFilesystem()->mkdir($this->getTempOriginalDir());
    }
    
    public function getPathOfTempFile($name)
    {
        return $this->getTempDir() . DIRECTORY_SEPARATOR . $name;
    }

    public function getHashOfTempFile($name)
    {
        return md5_file($this->getTempDir() . DIRECTORY_SEPARATOR . $name);
    }
    

    public function copyFileToTemporaryDirectory(FileInterface $file, $override = false)
    {
        $fileInfo = $this->getFileInfo($file);

        if (!$fileInfo->fileExist()){
            throw new FileNotFoundException($file->getName());
        }
        
        $this->getFilesystem()->copy($fileInfo->getPathOfFile(), $fileInfo->getPathOfTemporaryFile(), $override);
    }
    

    public function copyFileToPermenentDirectory(FileInterface $file, $override = false)
    {
        $fileInfo = $this->getFileInfo($file);

        if (!$fileInfo->tempFileExist()){
            throw new FileNotFoundException($file->getName());
        }
        
        $this->getFilesystem()->copy($fileInfo->getPathOfTemporaryFile(), $fileInfo->getPathOfFile(), $override);
    }
   
    
    public function removeFileFromTemporaryDirectory(FileInterface $file)
    {
        $fileInfo = $this->getFileInfo($file);
        
        $this->getFilesystem()->remove($fileInfo->getPathOfTemporaryFile());
    }
    
    public function removeFileFromPermenentDirectory(FileInterface $file)
    {
        $fileInfo = $this->getFileInfo($file);
        
        $this->getFilesystem()->remove($fileInfo->getPathOfFile());
    }
    
    public function removeAllFiles(FileInterface $file)
    {
        $this->removeFileFromPermenentDirectory($file);
        $this->removeFileFromTemporaryDirectory($file);
    }
}