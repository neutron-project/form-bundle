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

use Neutron\FormBundle\Model\MultiImageInterface;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
abstract class AbstractMultiImage extends AbstractImage 
    implements MultiImageInterface
{
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="position", length=10, nullable=false, unique=false)
     */
    protected $position = 0;
    
    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.MultiImageInterface::setPosition()
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;
    }
    
    /**
     * (non-PHPdoc)
     * @see Neutron\Bundle\FormBundle\Model.MultiImageInterface::getPosition()
     */
    public function getPosition()
    {
        return $this->position;
    }
}