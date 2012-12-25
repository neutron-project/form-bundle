<?php
namespace Neutron\FormBundle\Tests\Fixture\Entity;

use Neutron\FormBundle\Entity\AbstractMultiFile;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 */
class MultiFile extends AbstractMultiFile
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
        return '/media/files/main';
    }
}