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

use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Event\DataEvent;

use Neutron\FormBundle\Model\MultiImageInterface;

use Doctrine\ORM\PersistentCollection;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MultiImage form subscriber
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
     *  @var Neutron\FormBundle\Manager\ImageManagerInterface
     */
    protected $imageManager;

    /**
     * Construct
     * 
     * @param ObjectManager $om
     * @param ImageManagerInterface $imageManager
     */
    public function __construct(ObjectManager $om, ImageManagerInterface $imageManager)
    {
        $this->om = $om;
        $this->imageManager = $imageManager;
    }
    
    /**
     * Copy images from permenent to temporary directory
     * 
     * @param DataEvent $event
     */
    public function postSetData(DataEvent $event)
    {
        $collection = $event->getData();
        
        if ($collection instanceof Collection){
        
            foreach ($collection as $image){

                if ($image instanceof MultiImageInterface && null !== $image->getId()){
                    $this->imageManager->copyImagesToTemporaryDirectory($image);
                }
            }
        }
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
            FormEvents::POST_SET_DATA => 'postSetData',
            FormEvents::POST_BIND => 'postBind',
        );
    }
}