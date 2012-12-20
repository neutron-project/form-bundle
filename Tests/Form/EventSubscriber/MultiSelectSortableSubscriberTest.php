<?php
namespace Neutron\FormBundle\Tests\Form\EventSubscriber;

use Symfony\Component\Form\FormEvent;

use Neutron\FormBundle\Form\EventSubscriber\MultiSelectSortableSubscriber;

use Neutron\FormBundle\Tests\Fixture\Entity\Project;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiFile;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

use Symfony\Component\Form\FormEvents;

class MultiSelectSortableSubscriberTest extends BaseTestCaseORM
{
    
    const FIXTURE_PROJECT = 'Neutron\FormBundle\Tests\Fixture\Entity\Project';
    
    const FIXTURE_MULTI_FILE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiFile';
    
    const FIXTURE_MULTI_IMAGE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiImage';
    
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    public function testDefault()
    {
        $this->populate();
        
        $collection = $this->em->find(self::FIXTURE_PROJECT, 1)->getFiles();
        $dataEvent = $this->createDataEventMock($collection);
        $subscriber = new MultiSelectSortableSubscriber($this->em);
        $subscriber->preSetData($dataEvent);
        
        $collection->remove(1);
        $collection->remove(2);

        $subscriber->postBind($dataEvent);
        
        $this->assertCount(2, $this->em->getUnitOfWork()->getScheduledEntityDeletions());
        $this->em->flush();
        $this->em->clear();
        $collection = $this->em->find(self::FIXTURE_PROJECT, 1)->getFiles();
        
        $this->assertCount(2, $collection);
        
        $this->assertSame(array(FormEvents::PRE_SET_DATA => 'preSetData', FormEvents::POST_BIND => 'postBind'), 
            MultiSelectSortableSubscriber::getSubscribedEvents());
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
            ->expects($this->exactly(2))
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
            $multiFile = new MultiFile();
            $multiFile->setName('name' . $i);
            $multiFile->setHash(md5($i));
            $multiFile->setPosition($i);
            $this->em->persist($multiFile);
            $project->addFile($multiFile);
        }
        
        
        $this->em->flush();
        $this->em->clear();
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::FIXTURE_PROJECT, self::FIXTURE_MULTI_IMAGE, self::FIXTURE_MULTI_FILE);
    }
}