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
     * Sets image file md5 hash
     * Checks image is changed
     *
     * @param string $hash
     */
    public function setHash($hash);

    /**
     * Gets image file md5 hash
     *
     * @return string
     */
    public function getHash();

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
     * Sets image active state
     *
     * @param boolean $bool
     */
    public function setIsActive($bool);

    /**
     * Gets image active state
     *
     * @return boolean
     */
    public function getIsActive();

}