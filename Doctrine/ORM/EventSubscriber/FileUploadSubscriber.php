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

use Neutron\FormBundle\Exception\FileHashException;

use Neutron\FormBundle\Model\FileInterface;

use Neutron\FormBundle\Manager\FileManagerInterface;

use Doctrine\DBAL\LockMode;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\UnitOfWork;

use Doctrine\ORM\Events;

use Doctrine\ORM\Event\PostFlushEventArgs;

use Doctrine\ORM\Event\OnFlushEventArgs;

use Doctrine\Common\EventSubscriber;

/**
 * Doctrine ORM file upload subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class FileUploadSubscriber implements EventSubscriber
{
    /**
     * @var \Neutron\FormBundle\Manager\FileManagerInterface
     */
    protected $fileManager;
    
    /**
     * @var array
     */
    protected $scheduledForCopyFiles = array();
    
    /**
     * @var array
     */
    protected $scheduledForDeleteFiles = array();
    
    /**
     * @var boolean
     */
    protected $versionEnabled;

    /**
     * Construct
     * 
     * @param FileManagerInterface $fileManager
     * @param boolean $versionEnabled
     */
    public function __construct(FileManagerInterface $fileManager, $versionEnabled)
    { 
        $this->fileManager = $fileManager;
        $this->versionEnabled = $versionEnabled;
    }

    /**
     * Handles onFlush event and moves file to permenant directory
     *
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof FileInterface){
                $this->scheduledForCopyFiles[] = $entity;
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof FileInterface){
                
                if ($this->versionEnabled){
                    $em->lock($entity, LockMode::OPTIMISTIC, $entity->getCurrentVersion());
                }
                
                $changeSet = $uow->getEntityChangeSet($entity);

                if (isset($changeSet['name'])){
                    // remove old file
                    $clonedEntity = clone $entity;
                    $clonedEntity->setName($changeSet['name'][0]);
                    $this->scheduledForDeleteFiles[] = $clonedEntity; 
                } 
                
                if (true === $entity->isScheduledForDeletion()){
                    $uow->scheduleForDelete($entity);
                    continue;
                }

                if (isset($changeSet['hash'])){
                    $this->checksum($entity, $changeSet['hash'][1]);
                    $this->scheduledForCopyFiles[] = $entity;
                }
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof FileInterface){
                $this->scheduledForDeleteFiles[] = $entity;
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
        if (count($this->scheduledForCopyFiles) > 0){
      
            foreach ($this->scheduledForCopyFiles as $entity){
                $this->fileManager->copyFileToPermenentDirectory($entity);
                $this->fileManager->removeFileFromTemporaryDirectory($entity);
            }
            
            $this->scheduledForCopyFiles = array();
        }
        
        if (count($this->scheduledForDeleteFiles) > 0){
        
            foreach ($this->scheduledForDeleteFiles as $entity){
                $this->fileManager->removeAllFiles($entity);
            }
        
            $this->scheduledForDeleteFiles = array();
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
     * Checks if file is modified by some other user
     * 
     * @param FileInterface $entity
     * @param string $hash
     * @throws FileHashException
     */
    protected function checksum(FileInterface $entity, $hash)
    {
        $currentHash = $this->fileManager->getFileInfo($entity)->getTemporaryFileHash();
        
        if ($hash !== $currentHash){
            throw new FileHashException($entity->getName());
        }
    }
}