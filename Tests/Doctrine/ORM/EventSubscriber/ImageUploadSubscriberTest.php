<?php
namespace Neutron\FormBundle\Test\Doctrine\ORM\EventSubscriber;

use Neutron\FormBundle\Doctrine\ORM\EventSubscriber\ImageUploadSubscriber;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiImage;

use Doctrine\ORM\Events;

use Neutron\FormBundle\Tests\Fixture\Entity\Project;

use Doctrine\Common\EventManager;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

class ImageUploadSubscriberTest extends BaseTestCaseORM
{
    const FIXTURE_MULTI_IMAGE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiImage';

    public function testInsert()
    {
        $subscriber = new ImageUploadSubscriber($this->createImageManagerMock(1,0,0), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        $this->assertSame(array(Events::onFlush, Events::postFlush), $subscriber->getSubscribedEvents());
    }

    public function testUpdate()
    {
        $subscriber = new ImageUploadSubscriber($this->createImageManagerMock(2,1,1), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        
        $file = $this->em->find(self::FIXTURE_MULTI_IMAGE, 1);
        $file->setName('new name');
        $file->setHash('new hash');
        
        $this->em->flush();
    }
    
    public function testUpdateHash()
    {
        $subscriber = new ImageUploadSubscriber($this->createImageManagerMock(2,0,1), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        
        $entity = $this->em->find(self::FIXTURE_MULTI_IMAGE, 1);
        $entity->setName('name');
        $entity->setHash('new hash');
        
        $this->em->flush();
    }

    public function testRemove()
    {
        $subscriber = new ImageUploadSubscriber($this->createImageManagerMock(1,1,0), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        
        $entity = $this->em->find(self::FIXTURE_MULTI_IMAGE, 1);
        $entity->setHash(null);
        $entity->setScheduledForDeletion(true);
        
        $this->em->flush();
    }
    
    public function testInvalidHash()
    {
        $this->setExpectedException('Neutron\FormBundle\Exception\ImageHashException');
        
        $subscriber = new ImageUploadSubscriber($this->createImageManagerMock(1,0,1), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
    
        $entity = $this->em->find(self::FIXTURE_MULTI_IMAGE, 1);
        $entity->setName('image2');
        $entity->setHash('invalid hash');
    
        $this->em->flush();
    }
    
    protected function populate()
    {

        $multiImage = new MultiImage();
        $multiImage->setName('name');
        $multiImage->setHash('hash');
        $multiImage->setPosition(1);
        
        $this->em->persist($multiImage);
        $this->em->flush();
        $this->em->clear();
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::FIXTURE_MULTI_IMAGE);
    }
    
    protected function createImageManagerMock($m1 = 0, $m2 = 0, $m3 = 0)
    {
        $mock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
    
        $mock
            ->expects($this->exactly($m1))
            ->method('copyImagesToPermenentDirectory')
        ;
        
        $mock
            ->expects($this->exactly($m2))
            ->method('removeAllImages')
        ;
        
        $mock
            ->expects($this->any())
            ->method('getImageInfo')
            ->will($this->returnValue($this->createImageInfoMock($m3)))
        ;
    
        return $mock;
    }
    
    protected function createImageInfoMock($count)
    {
        $mock = $this->getMock('Neutron\FormBundle\Image\ImageInfoInterface');
    
        $mock
            ->expects($this->exactly($count))
            ->method('getTemporaryImageHash')
            ->will($this->returnValue('new hash'))
        ;
        
        return $mock;
    }
}