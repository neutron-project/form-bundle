<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\File;

use Neutron\FormBundle\Exception\FileEmptyException;

use Neutron\FormBundle\Manager\FileManagerInterface;

use Neutron\FormBundle\Model\FileInterface;

/**
 * Class that implements FileInterface
 *
 * @author Zender <azazen09@gmail.com>
 * @since 1.0
 */
class FileInfo implements FileInfoInterface
{
    /**
     * @var FileInterface
     */
    protected $file;
    
    /**
     * @var FileManagerInterface
     */
    protected $manager;
    
    /**
     * Construct
     * 
     * @param FileInterface $file
     * @param FileManagerInterface $manager
     */
    public function __construct(FileInterface $file, FileManagerInterface $manager)
    {
        $this->validateFile($file);
        $this->file = $file;
        $this->manager = $manager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::getFile()
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::getManager()
     */
    public function getManager()
    {
        return $this->manager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::getPathFileUploadDir()
     */
    public function getPathFileUploadDir()
    {
        return $this->getManager()->getWebDir() . DIRECTORY_SEPARATOR . trim($this->getFile()->getUploadDir(), '/');
    }
   
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::getPathOfTemporaryFile()
     */
    public function getPathOfTemporaryFile()
    {
        return $this->getManager()->getTempDir() . DIRECTORY_SEPARATOR  . $this->getFile()->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::getPathOfFile()
     */
    public function getPathOfFile()
    {
        return $this->getPathFileUploadDir() . DIRECTORY_SEPARATOR . $this->getFile()->getName();
    }
    
    public function getTemporaryFileHash()
    {
        if (is_file(realpath($this->getPathOfTemporaryFile()))){
            return md5_file($this->getPathOfTemporaryFile());
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::fileExist()
     */
    public function fileExist()
    {   
        return $this->getManager()->getFilesystem()->exists($this->getPathOfFile());
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\File\FileInfoInterface::tempFileExist()
     */
    public function tempFileExist()
    { 
        return $this->getManager()->getFilesystem()->exists($this->getPathOfTemporaryFile());
    }
    
    /**
     * Checks if file name is empty
     * 
     * @param FileInterface $file
     * @throws FileEmptyException
     */
    protected function validateFile(FileInterface $file)
    {
        $name = $file->getName();
        
        if (empty($name)){
            throw new FileEmptyException();
        }
    }
}