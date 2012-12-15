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

use Neutron\FormBundle\Model\FileInterface;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
abstract class AbstractFile implements FileInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name", length=255, nullable=false, unique=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="original_name", length=255, nullable=true, unique=false)
     */
    protected $originalName;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="size", length=20, nullable=true, unique=false)
     */
    protected $size;

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
     * @var string
     *
     * @ORM\Column(type="string", name="hash", length=255, nullable=false, unique=false)
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
     * Used to store current version of the file
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
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setName()
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setOriginalName()
     */
    public function setOriginalName($name)
    {
        $this->originalName = (string) $name;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getOriginalName()
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setSize()
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getSize()
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setTitle()
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getTitle()
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setCaption()
     */
    public function setCaption($caption)
    {
        $this->caption = (string) $caption;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getCaption()
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setDescription()
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::setHash()
     */
    public function setHash($hash)
    {
        $this->hash = (string) $hash;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getHash()
     */
    public function getHash()
    {
        return $this->hash;
    }

    public function setCurrentVersion($currentVersion)
    {
        $this->currentVersion = (int) $currentVersion;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\FileInterface::getCurrentVersion()
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
     * @see \Neutron\FormBundle\Model\FileInterface::getVersion()
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\FileInterface::setEnabled()
     */
    public function setEnabled($bool)
    {
        $this->enabled = (bool) $bool;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\FileInterface::isEnabled()
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\FileInterface::setScheduledForDeletion()
     */
    public function setScheduledForDeletion($bool)
    {
        $this->scheduledForDeletion = (bool) $bool;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Neutron\FormBundle\Model\FileInterface::isScheduledForDeletion()
     */
    public function isScheduledForDeletion()
    {
        return $this->scheduledForDeletion;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.FileInterface::getFilePath()
     */
    public function getFilePath()
    {
        return $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->getName();
    }
}