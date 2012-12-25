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

use Neutron\FormBundle\File\FileInfoInterface;

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
     * @var \Neutron\FormBundle\File\FileInfoInterface
     */
    protected $fileInfo;
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::setFilesystem()
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getFilesystem()
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::setRootDir()
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getRootDir()
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getWebDir()
     */
    public function getWebDir()
    {
        return $this->getRootDir() . '/../web';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::setTempDir()
     */
    public function setTempDir($tempDir)
    {
        $this->tempDir = $this->getWebDir() . DIRECTORY_SEPARATOR . $tempDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getTempDir()
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::setFileInfo()
     */
    public function setFileInfo(FileInfoInterface $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getFileInfo()
     */
    public function getFileInfo(FileInterface $file)
    {
        $this->fileInfo->setFile($file);
        $this->fileInfo->setManager($this);
        return $this->fileInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::createTemporaryDirectory()
     */
    public function createTemporaryDirectory()
    {
        $this->getFilesystem()->mkdir($this->getTempDir());
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getPathOfTempFile()
     */
    public function getPathOfTempFile($name)
    {
        return $this->getTempDir() . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::getHashOfTempFile()
     */
    public function getHashOfTempFile($name)
    {
        return md5_file($this->getTempDir() . DIRECTORY_SEPARATOR . $name);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::copyFileToPermenentDirectory()
     */
    public function copyFileToPermenentDirectory(FileInterface $file, $override = false)
    {
        $fileInfo = $this->getFileInfo($file);

        if (!$fileInfo->tempFileExists()){
            throw new FileNotFoundException($file->getName());
        }
        
        $this->getFilesystem()->copy($fileInfo->getPathOfTemporaryFile(), $fileInfo->getPathOfFile(), $override);
    }
   
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::removeFileFromTemporaryDirectory()
     */
    public function removeFileFromTemporaryDirectory(FileInterface $file)
    {
        $fileInfo = $this->getFileInfo($file);
        
        $this->getFilesystem()->remove($fileInfo->getPathOfTemporaryFile());
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::removeFileFromPermenentDirectory()
     */
    public function removeFileFromPermenentDirectory(FileInterface $file)
    {
        $fileInfo = $this->getFileInfo($file);
        
        $this->getFilesystem()->remove($fileInfo->getPathOfFile());
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\FileManagerInterface::removeAllFiles()
     */
    public function removeAllFiles(FileInterface $file)
    {
        $this->removeFileFromPermenentDirectory($file);
        $this->removeFileFromTemporaryDirectory($file);
    }
}