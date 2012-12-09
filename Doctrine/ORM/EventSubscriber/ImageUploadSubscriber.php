<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Doctrine\ORM\EventSubscriber;

use Neutron\FormBundle\Manager\ImageManagerInterface;

use Doctrine\ORM\Events;

use Doctrine\ORM\Event\PostFlushEventArgs;

use Neutron\FormBundle\Model\ImageInterface;

use Doctrine\ORM\Event\OnFlushEventArgs;

use Doctrine\Common\EventSubscriber;

/**
 * Doctrine ORM image upload subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImageUploadSubscriber implements EventSubscriber
{
    /**
     * @var \Neutron\FormBundle\Manager\ImageManagerInterface
     */
    protected $imageManager;
    
    /**
     * @var array
     */
    protected $scheduledForDeletion = array();

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
     * Handles onFlush event and moves images to permenant directory
     *
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof ImageInterface){
                $this->imageManager->copyImagesToPermenentDirectory($entity);
                //$this->imageManager->removeImagesFromTemporaryDirectory($entity);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof ImageInterface){

                $meta = $em->getClassMetadata(get_class($entity));
                $changeSet = $uow->getEntityChangeSet($entity);
                
                if (isset($changeSet['name']) && isset($changeSet['hash'])){
                    $this->imageManager->copyImagesToPermenentDirectory($entity);
                    
                    // remove old image
                    $clonedEntity = clone $entity;
                    $clonedEntity->setName($changeSet['name'][0]);
                    $clonedEntity->setHash($changeSet['hash'][0]);
                    //$this->imageManager->removeAllImages($clonedEntity); 
                    $this->imageManager->removeImagesFromPermenentDirectory($clonedEntity);
                    
                    if (empty($changeSet['name'][1]) || empty($changeSet['hash'][1])){
                        $this->scheduledForDeletion[] = $entity;
                    }
                    
                } else if (isset($changeSet['hash'])){ 
                    $this->imageManager->copyImagesToPermenentDirectory($entity);
                    //$this->imageManager->removeImagesFromTemporaryDirectory($entity);
                }
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof ImageInterface){
                $imageName = $entity->getName();
                
                if (!empty($imageName)){
                    $this->imageManager->removeAllImages($entity);
                }
                
            }
        }
    }
    
    /**
     * Handles postFlush event
     * 
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (count($this->scheduledForDeletion) > 0){
            $em = $eventArgs->getEntityManager();
            foreach ($this->scheduledForDeletion as $entity){
                $em->remove($entity);
            }
            $this->scheduledForDeletion = array();
            $em->flush();
        }
    }

    /**
     * (non-PHPdoc)
     * @see Doctrine\Common.EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postFlush);
    }
}