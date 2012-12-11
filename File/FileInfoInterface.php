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
 * Interface 
 *
 * @author  Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface FileInfoInterface
{
    public function getFile();
    
    public function getManager();
    
    public function getPathFileUploadDir();
     
    public function getPathOfTemporaryFile();
    
    public function getPathOfFile();
    
    public function getTemporaryFileHash();
    
    public function fileExist();
    
    public function tempFileExist();
}