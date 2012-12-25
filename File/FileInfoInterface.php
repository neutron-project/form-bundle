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

use Neutron\FormBundle\Exception\EmptyFileException;

use Neutron\FormBundle\Manager\FileManagerInterface;

use Neutron\FormBundle\Model\FileInterface;

/**
 * Interface that implements FileInfo class
 *
 * @author  Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface FileInfoInterface
{
    /**
     * Sets file
     * 
     * @param FileInterface $file
     * @return void
     */
    public function setFile(FileInterface $file);
    
    /**
     * Gets file
     * 
     * @return \Neutron\FormBundle\File
     */
    public function getFile();
    
    /**
     * Sets file manager
     * 
     * @param FileManagerInterface $manager
     * @return void
     */
    public function setManager(FileManagerInterface $manager);
    
    /**
     * Gets file manager
     * 
     * @return \Neutron\FormBundle\Manager\FileManagerInterface
     */
    public function getManager();
    
    /**
     * Returns permenent folder directory where file is located
     * 
     * @return string
     */
    public function getPathFileUploadDir();
     
    /**
     * Returns temporary file directory
     * 
     * @return string
     */
    public function getPathOfTemporaryFile();
    
    /**
     * Return path of permenent file
     * 
     * @return string
     */
    public function getPathOfFile();
    
    /**
     * Returns hash of temporary file
     * 
     * @return string
     */
    public function getTemporaryFileHash();
    
    /**
     * Checks if file exists
     * 
     * @return boolean
     */
    public function fileExists();
    
    /**
     * Checks id temporary file exists
     * 
     * @return string
     */
    public function tempFileExists();
}