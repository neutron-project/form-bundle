<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\Type;

use Neutron\FormBundle\Model\FileInterface;

use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery file upload element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class FileUploadType extends AbstractType
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session
     */
    protected $session;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;
    
    /**
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected $subscriber;

    /**
     * @var array
     */
    protected $options;

    /**
     * Construct
     * 
     * @param Session $session
     * @param Router $router
     * @param EventSubscriberInterface $subscriber
     * @param array $options
     */
    public function __construct(Session $session, Router $router, EventSubscriberInterface $subscriber, array $options)
    {
        $this->session = $session;
        $this->router = $router;
        $this->subscriber = $subscriber;
        $this->options = $options;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'hidden');
        $builder->add('originalName', 'hidden');
        $builder->add('size', 'hidden');
        $builder->add('title', 'hidden');
        $builder->add('caption', 'hidden');
        $builder->add('description', 'hidden');
        $builder->add('hash', 'hidden');
        $builder->add('enabled', 'hidden');
        $builder->add('currentVersion', 'hidden');
        $builder->add('scheduledForDeletion', 'hidden', array('data' => false));
        $builder->addEventSubscriber($this->subscriber);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $configs = array_merge($options['configs'], array(
            'id' => $view->vars['id'],        
            'name_id' => $view->getChild('name')->vars['id'],        
            'original_name_id' => $view->getChild('originalName')->vars['id'],        
            'size_id' => $view->getChild('size')->vars['id'],        
            'title_id' => $view->getChild('title')->vars['id'],        
            'caption_id' => $view->getChild('caption')->vars['id'],        
            'description_id' => $view->getChild('description')->vars['id'],        
            'hash_id' => $view->getChild('hash')->vars['id'],        
            'enabled_id' => $view->getChild('enabled')->vars['id'],   
            'scheduled_for_deletion_id' => $view->getChild('scheduledForDeletion')->vars['id'],   
            'enabled_value' => false,     
        ));
       
        $file = $form->getData();
        
        if ($file instanceof FileInterface && null !== $file->getId()){            
            $configs['enabled_value'] = $file->isEnabled();
        }
        
        $this->session->set($view->vars['id'], $configs);
        $view->vars['configs'] = $configs;
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultOptions = $this->options;
    
        $defaultConfigs = array(
            'maxSize' => $this->options['max_upload_size']
        );
    
        $router = $this->router;
    
        $resolver->setDefaults(array(
            'error_bubbling' => false,
            'translation_domain' => 'NeutronFormBundle',
            'configs' => $defaultConfigs,
        ));
    
        $resolver->setNormalizers(array(
            'configs' => function (Options $options, $value) use ($defaultOptions, $defaultConfigs, $router){
                $configs = array_replace_recursive($defaultOptions, $defaultConfigs, $value);

                $requiredConfigs = array('maxSize', 'extensions');

                if (count(array_diff($requiredConfigs, array_keys($configs))) > 0){
                    throw new \InvalidArgumentException(sprintf('Some of the configs "%s" are missing', json_encode($requiredConfigs)));
                }

                $configs['upload_url'] = $router->generate('neutron_form_media_file_upload');

                return $configs;
            }
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_file_upload';
    }

}