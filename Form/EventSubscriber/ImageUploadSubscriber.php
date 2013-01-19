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

use Neutron\FormBundle\Manager\ImageManagerInterface;

use Symfony\Component\Form\Event\DataEvent;

use Neutron\FormBundle\Model\ImageInterface;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Neutron image upload form event subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImageUploadSubscriber implements EventSubscriberInterface
{
    
    /**
     *  @var Neutron\FormBundle\Manager\ImageManagerInterface
     */
    protected $imageManager;

    /**
     * Construct
     * 
     * @param ImageManagerInterface $imageManager
     */
    public function __construct(ImageManagerInterface $imageManager)
    {
        $this->imageManager = $imageManager;
    }
    

    /**
     * Copy image to temporary directory
     *
     * @param FilterDataEvent $event
     * @return void
     */
    public function postSetData(DataEvent $event)
    {  
        $entity = $event->getData();

        if ($entity instanceof ImageInterface && null !== $entity->getId()){
            $this->imageManager->copyImagesToTemporaryDirectory($entity);
        }
    }
    
    /**
     * Form event - removes image if scheduled.
     * 
     * @param DataEvent $event
     */
    public function bind(DataEvent $event)
    {
        $entity = $event->getData();
        
        if ($entity instanceof ImageInterface && null === $entity->getId() && null === $entity->getName()){
            $event->setData(null);
        }
    }

    /**
     * Subscription for Form Events
     */
    static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SET_DATA => 'postSetData',
            FormEvents::BIND => 'bind',
        );
    }
}