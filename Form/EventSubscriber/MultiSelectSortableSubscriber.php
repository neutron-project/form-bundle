<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\EventSubscriber;

use Doctrine\ORM\PersistentCollection;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Event\DataEvent;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Form event subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class MultiSelectSortableSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Doctrine\ORM\PersistentCollection
     */
    protected $om;
    
    /**
     * Construct
     * 
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    /**
     * Data event - takes a snapshot of the collection
     * 
     * @param DataEvent $event
     */
    public function preSetData(DataEvent $event)
    {  
        $collection = $event->getData();
        
        if ($collection instanceof PersistentCollection){
            $collection->takeSnapshot();
        } 
    }
    
    /**
     * Form event - adds entities to uow to be delete
     * 
     * @param DataEvent $event
     */
    public function postBind(DataEvent $event)
    {
       $collection = $event->getData();
        
        if ($collection instanceof PersistentCollection){
            
            foreach ($collection->getDeleteDiff() as $entity) {
                $this->om->remove($entity);
            }
        }
    }

    /**
     * Subscription for Form Events
     */
    static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_BIND => 'postBind',
        );
    }
}