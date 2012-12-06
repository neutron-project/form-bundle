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

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Filesystem\Filesystem;

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

    protected $request;
    
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $webDir;

    /**
     * @var string
     */
    protected $tempDir;

    /**
     * Construct
     *
     * @param Filesystem $filesystem
     * @param string $webDir
     * @param array $options
     */
    public function __construct(Request $request, Filesystem $filesystem, $webDir, array $options)
    {
        $this->request = $request;
        $this->filesystem = $filesystem;
        $this->webDir = $webDir;
        $this->tempDir = $options['temp_dir'];
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

        if ($entity instanceof ImageInterface){
            
            $uploadDir = $this->webDir . DIRECTORY_SEPARATOR . trim($entity->getUploadDir(), '/');

            $imagePathTempOriginal = $this->tempDir . DIRECTORY_SEPARATOR .  'original' . DIRECTORY_SEPARATOR .$entity->getName();
            $imagePathOriginal = $uploadDir . DIRECTORY_SEPARATOR .  'original' . DIRECTORY_SEPARATOR .$entity->getName();

            $imagePathTemp = $this->tempDir . DIRECTORY_SEPARATOR  . $entity->getName();
            $imagePath = $uploadDir . DIRECTORY_SEPARATOR  . $entity->getName();

            if (file_exists($imagePathTemp) && (md5_file($imagePathTemp)) != $entity->getHash() 
                    && $this->request->getMethod() != 'POST'){
                $this->filesystem->remove($imagePathTemp);
            }
            
            if (file_exists($imagePathOriginal) && !file_exists($imagePathTempOriginal)){
            	$this->filesystem->copy($imagePathOriginal, $imagePathTempOriginal);
            }

            if (file_exists($imagePath) && !file_exists($imagePathTemp)){
            	$this->filesystem->copy($imagePath, $imagePathTemp);
            }

        }
    }
    
    public function bind(DataEvent $event)
    {
        $entity = $event->getData();
        
        if ($entity instanceof ImageInterface){
            if ($entity->getName() === '' || !$entity->getName()){ 
                $event->setData(null);
                $event->stopPropagation();
            }
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