<?php
namespace Neutron\FormBundle\Tests\Form\EventSubscriber;

use Neutron\FormBundle\Form\EventSubscriber\ImageUploadSubscriber;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Event\DataEvent;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class ImageUploadEventSubscriberTest extends BaseTestCase
{
    public function testBindEvent()
    {
        $subscriber = new ImageUploadSubscriber($this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface'));
        $subscriber->bind($this->createDataEventMock($this->createMockImage()));
        $this->assertSame(array(FormEvents::POST_SET_DATA => 'postSetData', FormEvents::BIND => 'bind'), ImageUploadSubscriber::getSubscribedEvents());
    }
    
    public function testPostSetDataEvent()
    {
        $subscriber = new ImageUploadSubscriber($this->createImageManagerMock());
        $subscriber->postSetData($this->createDataEventMock($this->createMockImage(1)));
        
    }
    
    protected function createImageManagerMock()
    {
        $mock = $this->getMock('Neutron\FormBundle\Manager\ImageManagerInterface');
        
        $mock
            ->expects($this->once())
            ->method('copyImagesToTemporaryDirectory')
            ->with($this->createMockImage())
            ->will($this->returnValue(null))
        ;
        
        return $mock;
    }
    
    protected function createDataEventMock($image, $count = 0)
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
            ->will($this->returnValue($image))
        ;
        
        $mock
            ->expects($this->any())
            ->method('setData')
            ->with(null)
        ;
        
        return $mock;
    }
    
    protected function createMockImage($id = null)
    {
        $mock =
            $this->getMock('Neutron\FormBundle\Tests\Fixture\Entity\Image');;
    
        $mock
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id))
        ;
    
        $mock
            ->expects($this->any())
            ->method('isScheduledForDeletion')
            ->will($this->returnValue(true))
        ;
    
        return $mock;
    }
}