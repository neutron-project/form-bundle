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
 * Image info class
 *
 * @author Zender <azazen09@gmail.com>
 * @since 1.0
 */
class ImageInfo implements ImageInfoInterface
{
    /**
     * @var ImageInterface
     */
    protected $image;
    
    /**
     * @var ImageManagerInterface
     */
    protected $manager;
    
    /**
     * Construct
     * 
     * @param ImageInterface $image
     * @param ImageManagerInterface $manager
     */
    public function __construct(ImageInterface $image, ImageManagerInterface $manager)
    {
        $this->validateImage($image);
        $this->image = $image;
        $this->manager = $manager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getImage()
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getManager()
     */
    public function getManager()
    {
        return $this->manager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getPathImageUploadDir()
     */
    public function getPathImageUploadDir()
    {
        return $this->getManager()->getWebDir() . DIRECTORY_SEPARATOR . trim($this->getImage()->getUploadDir(), '/');
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getPathOfTemporaryOriginalImage()
     */
    public function getPathOfTemporaryOriginalImage()
    {
        return $this->getManager()->getTempDir() . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $this->getImage()->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getPathOfOriginalImage()
     */
    public function getPathOfOriginalImage()
    {
        return $this->getPathImageUploadDir() . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $this->getImage()->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getPathOfTemporaryImage()
     */
    public function getPathOfTemporaryImage()
    {
        return $this->getManager()->getTempDir() . DIRECTORY_SEPARATOR  . $this->getImage()->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getPathOfImage()
     */
    public function getPathOfImage()
    {
        return $this->getPathImageUploadDir() . DIRECTORY_SEPARATOR . $this->getImage()->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getTemporaryImageHash()
     */
    public function getTemporaryImageHash()
    {
        if (is_file(realpath($this->getPathOfTemporaryImage()))){
            return md5_file($this->getPathOfTemporaryImage());
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::getTemporaryOriginalImageHash()
     */
    public function getTemporaryOriginalImageHash()
    {
        if (is_file(realpath($this->getPathOfTemporaryOriginalImage()))){
            return md5_file($this->getPathOfTemporaryOriginalImage());
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::imagesExist()
     */
    public function imagesExist()
    {   
        return $this->getManager()->getFilesystem()->exists(array(
            $this->getPathOfImage(),
            $this->getPathOfOriginalImage()       
        ));
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Image\ImageInfoInterface::tempImagesExist()
     */
    public function tempImagesExist()
    { 
        return $this->getManager()->getFilesystem()->exists(array(
            $this->getPathOfTemporaryImage(),
            $this->getPathOfTemporaryOriginalImage()
        ));
    }
    
    /**
     * Checks images if name or hash are empty
     * 
     * @param ImageInterface $image
     * @throws EmptyImageException
     * @return void
     */
    protected function validateImage(ImageInterface $image)
    {
        $name = $image->getName();
        $hash = $image->getHash();
        
        if (empty($name) || empty($hash)){
            throw new EmptyImageException();
        }
    }
}