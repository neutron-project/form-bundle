<?php
namespace Neutron\FormBundle\Tests\Fixture\Entity;

use Neutron\FormBundle\Entity\AbstractMultiImage;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 */
class MultiImage extends AbstractMultiImage
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
        return '/media/images';
    }
}