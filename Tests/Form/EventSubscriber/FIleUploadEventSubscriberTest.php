<?php
namespace Neutron\FormBundle\Tests\Form\EventSubscriber;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Event\DataEvent;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use Neutron\FormBundle\Form\EventSubscriber\FileUploadSubscriber;

class FileUploadEventSubscriberTest extends BaseTestCase
{
    public function testBindEvent()
    {
        $subscriber = new FileUploadSubscriber();
        $subscriber->bind($this->createDataEventMock());
        $this->assertSame(array(FormEvents::BIND => 'bind'), FileUploadSubscriber::getSubscribedEvents());
    }
    
    protected function createDataEventMock()
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
            ->will($this->returnValue($this->createMockFile()))
        ;
        
        $mock
            ->expects($this->once())
            ->method('setData')
            ->with(null)
        ;
        
        return $mock;
    }
    
    protected function createMockFile()
    {
        $mock =
            $this->getMock('Neutron\FormBundle\Tests\Fixture\Entity\File');;
    
        $mock
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(null))
        ;
    
        $mock
            ->expects($this->once())
            ->method('isScheduledForDeletion')
            ->will($this->returnValue(true))
        ;
    
        return $mock;
    }
}