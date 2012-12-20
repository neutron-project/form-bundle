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

use Neutron\FormBundle\Image\ImageInfoInterface;

use Neutron\FormBundle\Exception\TempImagesNotFoundException;

use Neutron\FormBundle\Exception\ImagesNotFoundException;

use Neutron\FormBundle\Model\ImageInterface;

use Neutron\FormBundle\Image\ImageInfo;

use Symfony\Component\Filesystem\Filesystem;

/**
 * This class is responsible for image paths and directories.
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImageManager implements ImageManagerInterface 
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
     * @var \Neutron\FormBundle\Image\ImageInfoInterface
     */
    protected $imageInfo;
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::setFilesystem()
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getFilesystem()
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::setRootDir()
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getRootDir()
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getWebDir()
     */
    public function getWebDir()
    {
        return $this->getRootDir() . '/../web';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::setTempDir()
     */
    public function setTempDir($tempDir)
    {
        $this->tempDir = $this->getWebDir() . DIRECTORY_SEPARATOR . $tempDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getTempDir()
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getTempOriginalDir()
     */
    public function getTempOriginalDir()
    {
        return $this->getTempDir() . DIRECTORY_SEPARATOR . 'original';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::setImageInfo()
     */
    public function setImageInfo(ImageInfoInterface $imageInfo)
    {
        $this->imageInfo = $imageInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getImageInfo()
     */
    public function getImageInfo(ImageInterface $image)
    {
        $this->imageInfo->setManager($this);
        $this->imageInfo->setImage($image);
        return $this->imageInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::createTemporaryDirectory()
     */
    public function createTemporaryDirectory()
    {
        $this->getFilesystem()->mkdir($this->getTempOriginalDir());
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::makeImageCopy()
     */
    public function makeImageCopy($name)
    {
        $originImage = $this->getTempOriginalDir() . DIRECTORY_SEPARATOR . $name;
        $targetImage = $this->getTempDir() . DIRECTORY_SEPARATOR . $name;
        $this->getFilesystem()->copy($originImage, $targetImage, true);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getPathOfTempOriginalImage()
     */
    public function getPathOfTempOriginalImage($name)
    {
        return $this->getTempOriginalDir() . DIRECTORY_SEPARATOR . $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getPathOfTempImage()
     */
    public function getPathOfTempImage($name)
    {
        return $this->getTempDir() . DIRECTORY_SEPARATOR . $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::getHashOfTempImage()
     */
    public function getHashOfTempImage($name)
    {
        return md5_file($this->getTempDir() . DIRECTORY_SEPARATOR . $name);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::copyImagesToTemporaryDirectory()
     */
    public function copyImagesToTemporaryDirectory(ImageInterface $image, $override = false)
    {
        $imageInfo = $this->getImageInfo($image);

        if (!$imageInfo->imagesExist()){
            throw new ImagesNotFoundException($image->getName());
        }
        
        $this->getFilesystem()->copy($imageInfo->getPathOfOriginalImage(), $imageInfo->getPathOfTemporaryOriginalImage(), $override);
        $this->getFilesystem()->copy($imageInfo->getPathOfImage(), $imageInfo->getPathOfTemporaryImage(), $override);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::copyImagesToPermenentDirectory()
     */
    public function copyImagesToPermenentDirectory(ImageInterface $image, $override = false)
    {
        $imageInfo = $this->getImageInfo($image);
        
        if (!$imageInfo->tempImagesExist()){
            throw new TempImagesNotFoundException($image->getName());
        }
        
        $this->getFilesystem()->copy($imageInfo->getPathOfTemporaryOriginalImage(), $imageInfo->getPathOfOriginalImage(), $override);
        $this->getFilesystem()->copy($imageInfo->getPathOfTemporaryImage(), $imageInfo->getPathOfImage(), $override);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::removeImagesFromTemporaryDirectory()
     */
    public function removeImagesFromTemporaryDirectory(ImageInterface $image)
    {
        $imageInfo = $this->getImageInfo($image);
        
        $this->getFilesystem()->remove(array(
            $imageInfo->getPathOfTemporaryImage(),
            $imageInfo->getPathOfTemporaryOriginalImage()
        ));
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::removeImagesFromPermenentDirectory()
     */
    public function removeImagesFromPermenentDirectory(ImageInterface $image)
    {
        $imageInfo = $this->getImageInfo($image);
        
        $this->getFilesystem()->remove(array(
            $imageInfo->getPathOfImage(),
            $imageInfo->getPathOfOriginalImage()
        ));
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::removeUnusedImages()
     */
    public function removeUnusedImages($maxAge)
    {   
        $delTime = (time() - (int) $maxAge);
        
        if (is_dir($this->getTempDir())){
            $iteratorTemp = new \DirectoryIterator($this->getTempDir());
            
            foreach ($iteratorTemp as $fileInfo){ 
                if ($fileInfo->isFile() && !$fileInfo->isDot()){
                    if ($delTime > $fileInfo->getMTime()){
                        $this->getFilesystem()->remove(($fileInfo->getRealPath()));
                    }
                }
            }
        }
        
        if (is_dir($this->getTempOriginalDir())){
            $iteratorOriginal = new \DirectoryIterator($this->getTempOriginalDir());
            
            foreach ($iteratorOriginal as $fileInfo){
                if ($fileInfo->isFile() && !$fileInfo->isDot()){
                    if ($delTime > $fileInfo->getMTime()){
                        $this->getFilesystem()->remove(($fileInfo->getRealPath()));
                    }
                }
            }
        }
        
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Manager\ImageManagerInterface::removeAllImages()
     */
    public function removeAllImages(ImageInterface $image)
    {
        $this->removeImagesFromPermenentDirectory($image);
        $this->removeImagesFromTemporaryDirectory($image);
    }
}