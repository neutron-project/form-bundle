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

use Doctrine\DBAL\LockMode;

use Neutron\FormBundle\Exception\ImageHashException;

use Neutron\FormBundle\Exception\ImagesNotFoundException;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\UnitOfWork;

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
    protected $scheduledForCopyImages = array();
    
    /**
     * @var array
     */
    protected $scheduledForDeleteImages = array();
    
    /**
     * @var boolean
     */
    protected $versionEnabled;

    /**
     * Construct
     * 
     * @param ImageManagerInterface $imageManager
     * @param boolean $versionEnabled
     */
    public function __construct(ImageManagerInterface $imageManager, $versionEnabled)
    { 
        $this->imageManager = $imageManager;
        $this->versionEnabled = $versionEnabled;
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
                $this->scheduledForCopyImages[] = $entity;
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof ImageInterface){
                
                if ($this->versionEnabled){
                    $em->lock($entity, LockMode::OPTIMISTIC, $entity->getCurrentVersion());
                }
                
                $changeSet = $uow->getEntityChangeSet($entity);

                if (isset($changeSet['name'])){
                    // remove old image
                    $clonedEntity = clone $entity;
                    $clonedEntity->setName($changeSet['name'][0]);
                    $this->scheduledForDeleteImages[] = $clonedEntity; 
                } 
                
                if (true === $entity->isScheduledForDeletion()){
                    $uow->scheduleForDelete($entity);
                    continue;
                }

                if (isset($changeSet['hash'])){
                    $this->checksum($entity, $changeSet['hash'][1]);
                    $this->scheduledForCopyImages[] = $entity;
                }
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof ImageInterface){
                $this->scheduledForDeleteImages[] = $entity;
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
        if (count($this->scheduledForCopyImages) > 0){
      
            foreach ($this->scheduledForCopyImages as $entity){
                $this->imageManager->copyImagesToPermenentDirectory($entity);
            }
            
            $this->scheduledForCopyImages = array();
        }
        
        if (count($this->scheduledForDeleteImages) > 0){
        
            foreach ($this->scheduledForDeleteImages as $entity){
                $this->imageManager->removeAllImages($entity);
            }
        
            $this->scheduledForDeleteImages = array();
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
    
    /**
     * Checks if images is modified by some other user
     * 
     * @param ImageInterface $entity
     * @param string $hash
     * @throws ImageHashException
     */
    protected function checksum(ImageInterface $entity, $hash)
    {
        $currentHash = $this->imageManager->getImageInfo($entity)->getTemporaryImageHash();
    
        if ($hash !== $currentHash){
            throw new ImageHashException($entity->getName());
        }
    }
}