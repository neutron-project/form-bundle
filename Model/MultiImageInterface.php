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
 * Interface that implements multi image
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface MultiImageInterface extends ImageInterface
{
    /**
     * Sets image position
     *
     * @param integer $position
     */
    public function setPosition($position);

    /**
     * Returns position of the image
     *
     * @return integer
     */
    public function getPosition();

}