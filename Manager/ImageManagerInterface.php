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

use Neutron\FormBundle\Model\ImageInterface;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Interface
 *
 * @author Zender <azazen09@gmail.com>
 * @since 1.0
 */
interface ImageManagerInterface
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
     * Gets temporary directory of original images
     * 
     * @return string
     */
    public function getTempOriginalDir();
    
    /**
     * Sets image info 
     * 
     * @param ImageInfoInterface $imageInfo
     * @return void
     */
    public function setImageInfo(ImageInfoInterface $imageInfo);
    
    /**
     * Gets image info
     * 
     * @param ImageInterface $image
     * @return ImageInfoInterface
     */
    public function getImageInfo(ImageInterface $image);
    
    /**
     * Creates image temporary directories in web root (by default temp/original)
     * 
     * @return void
     */
    public function createTemporaryDirectory();
    
    /**
     *  Copies image form original directory to temp directory
     *  
     *  @return void
     */
    public function makeImageCopy($name);
    
    /**
     * Gets path of temporary original image by given image name
     * 
     * @param string $name
     * @return string
     */
    public function getPathOfTempOriginalImage($name);
    
    /**
     * Gets path of temporary image by given image name
     * 
     * @param string $name
     * @return string
     */
    public function getPathOfTempImage($name);
    
    /**
     * Gets hash of temporary image by name
     * 
     * @param string $name
     * @return string
     */
    public function getHashOfTempImage($name);
    
    /**
     * Copies images from permenent to temporary directory
     * 
     * @param ImageInterface $image
     * @param string $override
     * @return void
     * @throws ImagesNotFoundException
     */
    public function copyImagesToTemporaryDirectory(ImageInterface $image, $override = false);
    
    /**
     * Copies images from temporary to permenent directory
     * 
     * @param ImageInterface $image
     * @param string $override
     * @return void
     * @throws TempImagesNotFoundException
     */
    public function copyImagesToPermenentDirectory(ImageInterface $image, $override = false);
    
    /**
     * Removes images form temporary directory
     * 
     * @param ImageInterface $image
     * @return void
     */
    public function removeImagesFromTemporaryDirectory(ImageInterface $image);
    
    /**
     * Remove images form permenent directory
     * 
     * @param ImageInterface $image
     * @return void
     */
    public function removeImagesFromPermenentDirectory(ImageInterface $image);
    
    /**
     * Remove unused images from temporary directory by checking mtime
     * 
     * @param integer $maxAge
     * @return void
     */
    public function removeUnusedImages($maxAge);
    
    /**
     * Triggers removeImagesFromTemporaryDirectory, removeImagesFromPermenentDirectory
     * 
     * @param ImageInterface $image
     * @return void
     */
    public function removeAllImages(ImageInterface $image);
}