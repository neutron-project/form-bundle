<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Model;

/**
 * Interface that implements image
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface ImageInterface
{
    /**
     * Returns the ID of the image
     */
    public function getId();
    
    /**
     * Sets filename of the image
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Returns filename of the image
     *
     * @return string
     */
    public function getName();

    /**
     * Sets image title
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Gets image title
     *
     * @return string | null
     */
    public function getTitle();

    /**
     * Sets image caption
     *
     * @param string $caption
     */
    public function setCaption($caption);

    /**
     * Gets image caption
     *
     * @return string | null
     */
    public function getCaption();

    /**
     * Sets image description
     *
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Gets image description
     *
     * @return string | null
     */
    public function getDescription();
    
    /**
     * Sets hash of the image
     * 
     * @param integer $mtime
     */
    public function setHash($hash);

    /**
     * Gets image hash time 
     *
     * @return string
     */
    public function getHash();
    
    /**
     * Returns database version of the image
     */
    public function getVersion();
    
    /**
     * Sets current version of the image
     * 
     * @param integer $currentVersion
     */
    public function setCurrentVersion($currentVersion);
    
    /**
     * Gets current version of the image
     */
    public function getCurrentVersion();
    
    /**
     * Gets image upload directory
     * It suggests it is located in "web" directory
     *
     * @return string
     */
    public function getUploadDir();

    /**
     * Get path to image
     *
     * @return string
     */
    public function getImagePath();

    /**
     * Enabled image
     *
     * @param boolean $bool
     */
    public function setEnabled($bool);

    /**
     * Check if image is enabled
     *
     * @return boolean
     */
    public function isEnabled();
    
    /**
     * Schedules image for deletion
     * 
     * @param boolean $bool
     */
    public function setScheduledForDeletion($bool);
    
    /**
     * Checks if image is scheduled for deletion
     * 
     * @return bool
     * @return void
     */
    public function isScheduledForDeletion();

}