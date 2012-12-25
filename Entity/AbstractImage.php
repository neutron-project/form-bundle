<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Entity;

use Neutron\FormBundle\Model\ImageInterface;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
abstract class AbstractImage implements ImageInterface
{
    /**
     * 
     * @var integer
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="name", length=255, nullable=false, unique=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="title", length=255, nullable=true, unique=false)
     */
    protected $title;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="caption", length=255, nullable=true, unique=false)
     */
    protected $caption;

    /**
     * @var string
     * 
     * @ORM\Column(type="text", name="description", nullable=true)
     */
    protected $description;
    
    /**
     * @var integer
     *
     * @ORM\Column(type="string", name="hash", length=40, nullable=false, unique=false)
     */
    protected $hash;

    /** 
     * @var integer
     * 
     * @ORM\Version @ORM\Column(type="integer") 
     */
    protected $version;
    
    /**
     * This property is not mapped by Doctrine.
     * Used to store current version of the image
     * 
     * @var integer
     */
    protected $currentVersion;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled")
     */
    protected $enabled = false;
    
    /**
     * This property is not mapped by Doctrine.
     * Used to identify if entity is marked for deletion
     * 
     * @var boolean
     */
    protected $scheduledForDeletion = false;
    
    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::setName()
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::setTitle()
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::getTitle()
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::setCaption()
     */
    public function setCaption($caption)
    {
        $this->caption = (string) $caption;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::getCaption()
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::setDescription()
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.ImageInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::setCurrentVersion()
     */
    public function setCurrentVersion($currentVersion)
    {
        $this->currentVersion = (int) $currentVersion;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::getCurrentVersion()
     */
    public function getCurrentVersion()
    {
        if (null === $this->currentVersion){
            $this->currentVersion = $this->version;
        }
        
        return $this->currentVersion;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::getVersion()
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::setHash()
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::getHash()
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::setEnabled()
     */
    public function setEnabled($bool)
    {
        $this->enabled = (bool) $bool;
    }

    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::isEnabled()
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::setScheduledForDeletion()
     */
    public function setScheduledForDeletion($bool)
    {
        $this->scheduledForDeletion = (bool) $bool;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::isScheduledForDeletion()
     */
    public function isScheduledForDeletion()
    {
        return $this->scheduledForDeletion;
    }

    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\ImageInterface::getImagePath()
     */
    public function getImagePath()
    {
    	return $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->getName();
    }
}