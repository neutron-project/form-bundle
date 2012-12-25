<?php
namespace Neutron\FormBundle\Test\Doctrine\ORM\EventSubscriber;

use Doctrine\ORM\Events;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiFile;

use Neutron\FormBundle\Tests\Fixture\Entity\Project;

use Neutron\FormBundle\Doctrine\ORM\EventSubscriber\FileUploadSubscriber;

use Doctrine\Common\EventManager;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

class FileUploadSubscriberTest extends BaseTestCaseORM
{
    const FIXTURE_MULTI_FILE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiFile';

    public function testInsert()
    {
        $subscriber = new FileUploadSubscriber($this->createFileManagerMock(1,1,0), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        $this->assertSame(array(Events::onFlush, Events::postFlush), $subscriber->getSubscribedEvents());
    }

    public function testUpdate()
    {
        $subscriber = new FileUploadSubscriber($this->createFileManagerMock(2,2,1,1), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        
        $file = $this->em->find(self::FIXTURE_MULTI_FILE, 1);
        $file->setName('file2');
        $file->setHash('new hash');
        
        $this->em->flush();
    }

    public function testRemove()
    {
        $subscriber = new FileUploadSubscriber($this->createFileManagerMock(1,1,1), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
        
        $file = $this->em->find(self::FIXTURE_MULTI_FILE, 1);
        $file->setHash(null);
        $file->setScheduledForDeletion(true);
        
        $this->em->flush();
    }
    
    public function testInvalidHash()
    {
        $this->setExpectedException('Neutron\FormBundle\Exception\FileHashException');
        
        $subscriber = new FileUploadSubscriber($this->createFileManagerMock(1,1,0,1), true);
        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);
        $this->createMockEntityManager($evm);
        $this->populate();
    
        $file = $this->em->find(self::FIXTURE_MULTI_FILE, 1);
        $file->setName('file2');
        $file->setHash('invalid hash');
    
        $this->em->flush();
    }
    
    protected function populate()
    {

        $multiFile = new MultiFile();
        $multiFile->setName('name');
        $multiFile->setHash('hash');
        $multiFile->setPosition(1);
        
        $this->em->persist($multiFile);
        $this->em->flush();
        $this->em->clear();
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::FIXTURE_MULTI_FILE);
    }
    
    protected function createFileManagerMock($m1 = 0, $m2 = 0, $m3 = 0, $m4 = 0)
    {
        $mock = $this->getMock('Neutron\FormBundle\Manager\FileManagerInterface');
    
        $mock
            ->expects($this->exactly($m1))
            ->method('copyFileToPermenentDirectory')
        ;
        
        $mock
            ->expects($this->exactly($m2))
            ->method('removeFileFromTemporaryDirectory')
        ;
        
        $mock
            ->expects($this->exactly($m3))
            ->method('removeAllFiles')
        ;
        
        $mock
            ->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue($this->createFileInfoMock($m4)))
        ;
    
        return $mock;
    }
    
    protected function createFileInfoMock($count)
    {
        $mock = $this->getMock('Neutron\FormBundle\File\FileInfoInterface');
    
        $mock
            ->expects($this->exactly($count))
            ->method('getTemporaryFileHash')
            ->will($this->returnValue('new hash'))
        ;
        
        return $mock;
    }
}