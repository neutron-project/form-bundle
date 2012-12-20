<?php
namespace Neutron\FormBundle\Tests\Form\EventSubscriber;

use Neutron\FormBundle\Tests\Fixture\Entity\Project;

use Neutron\FormBundle\Tests\Fixture\Entity\MultiFile;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

use Neutron\FormBundle\Form\EventSubscriber\MultiFileUploadSubscriber;

use Symfony\Component\Form\FormEvents;

class MultiFileUploadEventSubscriberTest extends BaseTestCaseORM
{
    
    const FIXTURE_PROJECT = 'Neutron\FormBundle\Tests\Fixture\Entity\Project';
    
    const FIXTURE_MULTI_FILE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiFile';
    
    const FIXTURE_MULTI_IMAGE = 'Neutron\FormBundle\Tests\Fixture\Entity\MultiImage';
    
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    public function testPostBindEvent()
    {
        $this->populate();
        
        $collection = $this->em->find(self::FIXTURE_PROJECT, 1)->getFiles();
        
        $collection->remove(1);
        $collection->remove(2);
        
        $subscriber = new MultiFileUploadSubscriber($this->em);
        
        $subscriber->postBind($this->createDataEventMock($collection));
        
        $this->assertCount(2, $this->em->getUnitOfWork()->getScheduledEntityDeletions());
        $this->assertSame(array(FormEvents::POST_BIND => 'postBind'), MultiFileUploadSubscriber::getSubscribedEvents());
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
            ->expects($this->once())
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