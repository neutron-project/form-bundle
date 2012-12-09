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

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Event\DataEvent;

use Neutron\FormBundle\Model\MultiImageInterface;

use Doctrine\ORM\PersistentCollection;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Image form subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class MultiImageUploadSubscriber implements EventSubscriberInterface
{
    /**
     * @var Doctrine\Common\Persistence\ObjectManager
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
     * Remove deleted images
     * 
     * @param DataEvent $event
     */
    public function postBind(DataEvent $event)
    {
        $collection = $event->getData();

        if ($collection instanceof PersistentCollection){

            foreach ($collection->getDeleteDiff() as $entity){
                if ($entity instanceof MultiImageInterface){
                    $this->om->remove($entity);
                } 
            }
        }
    }

    /**
     * Register callbacks
     */
    static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_BIND => 'postBind',
        );
    }
}