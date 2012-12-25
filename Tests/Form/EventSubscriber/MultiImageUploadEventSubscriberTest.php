<?php
namespace Neutron\FormBundle\Tests\Form\EventSubscriber;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiImage;

use Neutron\FormBundle\Form\EventSubscriber\MultiImageUploadSubscriber;

use Neutron\FormBundle\Tests\Fixture\Entity\Project;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiFile;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

use Symfony\Component\Form\FormEvents;

class MultiImageUploadEventSubscriberTest extends BaseTestCaseORM
{
    
    const FIXTURE_PROJECT = 'Neutron\FormBundle\Tests\Fixture\Entity\Project';
    
    const FIXTURE_MULTI_IMAGE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiImage';
    
    const FIXTURE_MULTI_FILE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiFile';
    
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    public function testPostSetDataEvent()
    {
        $this->populate();
        
        $collection = $this->em->find(self::FIXTURE_PROJECT, 1)->getImages();

        $subscriber = new MultiImageUploadSubscriber($this->em, $this->createImageManagerMock());
        $subscriber->postSetData($this->createDataEventMock($collection));
        $this->assertSame(array(FormEvents::POST_SET_DATA => 'postSetData', FormEvents::POST_BIND => 'postBind'), 
            MultiImageUploadSubscriber::getSubscribedEvents());
    }
    
    public function testPostBindEvent()
    {
        $this->populate();
        
        $collection = $this->em->find(self::FIXTURE_PROJECT, 1)->getImages();
        
        $collection->remove(1);
        $collection->remove(2);
        
        $subscriber = new MultiImageUploadSubscriber($this->em, $this->createImageManagerMock());
        
        $subscriber->postBind($this->createDataEventMock($collection));
        
        $this->assertCount(2, $this->em->getUnitOfWork()->getScheduledEntityDeletions());
        
    }
    
    protected function createImageManagerMock()
    {
        $mock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
    
        $mock
            ->expects($this->any())
            ->method('copyImagesToTemporaryDirectory')
        ;
    
        return $mock;
    }
    
    protected function createDataEventMock($collection)
    {
        $mock = 
            $this
                ->getMockBuilder('Symfony\Component\Form\Event\DataEvent')
                ->disableOriginalConstructor()
                ->getMock()
            ;
        
        $mock
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($collection))
        ;

        return $mock;
    }
    
    
    protected function populate()
    {
        $project = new Project();
        $project->setTitle('project');
        $this->em->persist($project);
        
        for ($i = 1; $i < 5; $i++){
            $image = new MultiImage();
            $image->setName('name' . $i);
            $image->setHash(md5($i));
            $image->setPosition($i);
            $this->em->persist($image);
            $project->addImage($image);
        }
        
        
        $this->em->flush();
        $this->em->clear();
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::FIXTURE_PROJECT, self::FIXTURE_MULTI_IMAGE, self::FIXTURE_MULTI_FILE);
    }
}