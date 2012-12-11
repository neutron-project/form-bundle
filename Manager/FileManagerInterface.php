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
    public function setFilesystem(Filesystem $filesystem);
    
    public function getFilesystem();
      
    public function setRootDir($rootDir);
      
    public function getRootDir();
    
    public function getWebDir();
       
    public function setTempDir($tempDir);
       
    public function getTempDir();
     
    public function getFileInfo(FileInterface $file);
    
    public function createTemporaryDirectory();
    
    public function getPathOfTempFile($name);
    
    public function getHashOfTempFile($name);
      
    public function copyFileToTemporaryDirectory(FileInterface $file, $override = false);
    
    public function copyFileToPermenentDirectory(FileInterface $file, $override = false);
         
    public function removeFileFromTemporaryDirectory(FileInterface $file);
    
    public function removeFileFromPermenentDirectory(FileInterface $file);
    
    public function removeAllFiles(FileInterface $file);
}