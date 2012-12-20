<?php
namespace Neutron\FormBundle\Tests\Fixture\Entity;

use Neutron\FormBundle\Entity\AbstractImage;

class Image extends AbstractImage
{
    /**
     * @var integer
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function getUploadDir()
    {
        return '/media/images/main';
    }
}