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

use Neutron\FormBundle\File\FileInfoInterface;

use Neutron\FormBundle\File\FileInfo;

use Neutron\FormBundle\Model\FileInterface;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Interface
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface FileManagerInterface
{
    /**
     * Sets filesystem service
     *
     * @param Filesystem $filesystem
     * @return void
     */
    public function setFilesystem(Filesystem $filesystem);
    
    /**
     * Gets filesystem service
     *
     * @return Filesystem
     */
    public function getFilesystem();
      
    /**
     * Sets application root directory
     *
     * @param string $rootDir
     * @return void
     */
    public function setRootDir($rootDir);
      
    /**
     * Gets application root directory
     *
     * @return string
     */
    public function getRootDir();
    
    /**
     * Get web directory
     *
     * @return string
     */
    public function getWebDir();
       
    /**
     * Sets temporary web directory
     *
     * @param string $tempDir
     * @return void
     */
    public function setTempDir($tempDir);
       
    /**
     * Gets temporary web directory
     *
     * @return string
     */
    public function getTempDir();
     
    /**
     * Sets fileinfo
     * 
     * @param FileInfoInterface $fileInfo
     * @return void
     */
    public function setFileInfo(FileInfoInterface $fileInfo);
    
    /**
     * Gets file info
     * 
     * @param FileInterface $file
     * @return FileInfoInterface
     */
    public function getFileInfo(FileInterface $file);
    
    /**
     * Creates image temporary directories in web root (by default temp)
     * 
     * @return void
     */
    public function createTemporaryDirectory();
    
    /**
     * Gets path of temporary file by given name
     * 
     * @param string $name
     * @return string
     */
    public function getPathOfTempFile($name);
    
    /**
     * Gets hash of temporary file by given name
     * 
     * @param string $name
     * @return string
     */
    public function getHashOfTempFile($name);
    
    /**
     * Copies file to permenent directory
     * 
     * @param FileInterface $file
     * @param string $override
     * @return void
     */
    public function copyFileToPermenentDirectory(FileInterface $file, $override = false);
         
    /**
     * Removes file from temporary directory
     * 
     * @param FileInterface $file
     * @return void
     */
    public function removeFileFromTemporaryDirectory(FileInterface $file);
    
    /**
     * Removes file from permenent directory
     * 
     * @param FileInterface $file
     * @return void
     */
    public function removeFileFromPermenentDirectory(FileInterface $file);
    
    /**
     * Executes removeFileFromTemporaryDirectory and removeFileFromPermenentDirectory
     * 
     * @param FileInterface $file
     * @return void
     */
    public function removeAllFiles(FileInterface $file);
}