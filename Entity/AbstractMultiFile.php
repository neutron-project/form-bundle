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

use Neutron\FormBundle\Model\MultiFileInterface;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
abstract class AbstractMultiFile extends AbstractFile
    implements MultiFileInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="position", length=10, nullable=false, unique=false)
     */
    protected $position;

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.MultiFileInterface::setPosition()
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;
    }

    /**
     * (non-PHPdoc)
     * @see Neutron\FormBundle\Model.MultiFileInterface::getPosition()
     */
    public function getPosition()
    {
        return $this->position;
    }
}