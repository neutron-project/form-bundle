<?php
namespace Neutron\FormBundle\Tests\Entity;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiFile;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

class MultiFileTest extends BaseTestCaseORM
{
    const ENTITY = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiFile';
    
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    public function testDefault()
    {
        $entity = new MultiFile();
        $entity->setName('file.txt');
        $entity->setOriginalName('original.txt');
        $entity->setSize(123456);
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
        $this->assertSame('file.txt', $entity->getName());
        $this->assertSame('original.txt', $entity->getOriginalName());
        $this->assertSame(123456, $entity->getSize());
        $this->assertSame('hash', $entity->getHash());
        $this->assertSame('title', $entity->getTitle());
        $this->assertSame('caption', $entity->getCaption());
        $this->assertSame('desc', $entity->getDescription());
        $this->assertSame(1, $entity->getVersion());
        $this->assertTrue($entity->isEnabled());
        $this->assertTrue($entity->isScheduledForDeletion());
        $this->assertSame(1, $entity->getPosition());
        $this->assertSame('/media/files/main', $entity->getUploadDir());
        $this->assertSame('/media/files/main/file.txt', $entity->getFilePath());
        
        $entity->setCurrentVersion(2);
        
        
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::ENTITY);
    }
}