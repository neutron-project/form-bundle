<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Image;

use Neutron\FormBundle\Exception\EmptyImageException;

use Neutron\FormBundle\Manager\ImageManagerInterface;

use Neutron\FormBundle\Model\ImageInterface;

/**
 * Interface
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface ImageInfoInterface
{
    
    /**
     * Sets image
     * 
     * @param ImageInterface $image
     * @return void
     */
    public function setImage(ImageInterface $image);
    
    /**
     * Gets image entity
     * 
     * @return ImageInterface
     */
    public function getImage();
    
    /**
     * Sets image manager
     * 
     * @param ImageManagerInterface $manager
     * @return void
     */
    public function setManager(ImageManagerInterface $manager);
    
    /**
     * Gets image manager
     * 
     * @return ImageManagerInterface
     */
    public function getManager();
    
    /**
     * Returns permenent path of the images
     * 
     * @return string
     */
    public function getPathImageUploadDir();
    
    /**
     * Returns temporary path of original images
     * 
     * @return string
     */
    public function getPathOfTemporaryOriginalImage();
    
    /**
     * Returns permenent path of original images
     * 
     * @return string
     */
    public function getPathOfOriginalImage();
    
    /**
     * Return path of temporary original image
     * 
     * @return string
     */
    public function getPathOfTemporaryImage();
    
    /**
     * Return path of temporary image
     * 
     * @return string
     */
    public function getPathOfImage();
    
    /**
     * Return hash of temporary image
     * 
     * @return string
     */
    public function getTemporaryImageHash();
    
    /**
     * Checks if both permenent images exist (original and modified)
     * 
     * @return bool
     */
    public function imagesExist();
    
    /**
     * Checks if both temporary images exist (original and modified)
     * 
     * @return bool
     */
    public function tempImagesExist();
}