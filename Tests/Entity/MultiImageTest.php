<?php
namespace Neutron\FormBundle\Tests\Entity;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiImage;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

class MultiImageTest extends BaseTestCaseORM
{
    const ENTITY = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiImage';
    
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    public function testDefault()
    {
        $entity = new MultiImage();
        $entity->setName('test.jpg');
        $entity->setHash('hash');
        $entity->setTitle('title');
        $entity->setCaption('caption');
        $entity->setDescription('desc');
        $entity->setEnabled(true);
        $entity->setScheduledForDeletion(true);
        $entity->setPosition(1);
        
        $this->assertNull($entity->getCurrentVersion());
        
        $this->em->persist($entity);
        $this->em->flush($entity);

        
        $this->assertNotNull($entity->getId());
        $this->assertSame('test.jpg', $entity->getName());
        $this->assertSame('hash', $entity->getHash());
        $this->assertSame('title', $entity->getTitle());
        $this->assertSame('caption', $entity->getCaption());
        $this->assertSame('desc', $entity->getDescription());
        $this->assertSame(1, $entity->getVersion());
        $this->assertTrue($entity->isEnabled());
        $this->assertTrue($entity->isScheduledForDeletion());
        $this->assertSame(1, $entity->getPosition());
        $this->assertSame('/media/images', $entity->getUploadDir());
        $this->assertSame('/media/images/test.jpg', $entity->getImagePath());
        
        $entity->setCurrentVersion(2);
        
        
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::ENTITY);
    }
}