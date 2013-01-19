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

use Neutron\FormBundle\Model\FileInterface;

use Symfony\Component\Form\Event\DataEvent;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Neutron file upload form event subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class FileUploadSubscriber implements EventSubscriberInterface
{
     
    /**
     * Form event - removes file if scheduled.
     * 
     * @param DataEvent $event
     */
    public function bind(DataEvent $event)
    {
        $entity = $event->getData();
        
        if ($entity instanceof FileInterface && null === $entity->getId() && null === $entity->getName()){
            $event->setData(null);
        }
    }

    /**
     * Subscription for Form Events
     */
    static function getSubscribedEvents()
    {
        return array(
            FormEvents::BIND => 'bind',
        );
    }
}