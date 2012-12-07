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

use Neutron\FormBundle\Exception\ImagesNotFoundException;

use Neutron\FormBundle\Manager\ImageManagerInterface;

use Neutron\FormBundle\Model\ImageInterface;

use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormBuilderInterface;

use Neutron\FormBundle\EventSubscriber\Form\ImageUploadSubscriber;

use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery image upload element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImageUploadType extends AbstractType
{    
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;
    
    /**
     * @var \Neutron\FormBundle\Manager\ImageManagerInterface
     */
    protected $imageManager;

    /**
     * @var array
     */
    protected $options;


    /**
     * Construct 
     * 
     * @param Session $session
     * @param Router $router
     * @param ImageManagerInterface $imageManager
     * @param array $options
     */
    public function __construct(Session $session, Router $router, ImageManagerInterface $imageManager, array $options)
    {
        $this->session = $session;
        $this->router = $router;
        $this->imageManager = $imageManager;
        $this->options = $options;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'hidden');
        $builder->add('title', 'hidden');
        $builder->add('caption', 'hidden');
        $builder->add('description', 'hidden');
        $builder->add('hash', 'hidden');
        $builder->add('enabled', 'hidden');
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::finishView()
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $configs = array_merge($options['configs'], array(
            'id' => $view->vars['id'],        
            'name_id' => $view->getChild('name')->vars['id'],        
            'title_id' => $view->getChild('title')->vars['id'],        
            'caption_id' => $view->getChild('caption')->vars['id'],        
            'description_id' => $view->getChild('description')->vars['id'],        
            'hash_id' => $view->getChild('hash')->vars['id'],        
            'enabled_id' => $view->getChild('enabled')->vars['id'],        
        ));
       
        $image = $form->getData();
        
        if ($image instanceof ImageInterface && null !== $image->getId()){
            $override = ($image->getHash() != $this->imageManager->getImageInfo($image)->getTemporaryImageHash());
            
            try {
                $this->imageManager->copyImagesToTemporaryDirectory($form->getData(), $override);
            } catch (ImagesNotFoundException $e){
                // do nothing
            }
            
        }
        
        $this->session->set($view->vars['id'], $configs);
        $view->vars['configs'] = $configs;
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultOptions = $this->options; 
        
        $router = $this->router;
        
        $resolver->setDefaults(array(
            'error_bubbling' => false,
            'translation_domain' => 'NeutronFormBundle',
            'configs' => array(),
        ));
    
        $resolver->setNormalizers(array(
            'configs' => function (Options $options, $value) use ($defaultOptions, $router){
                $configs = array_replace_recursive($defaultOptions, $value);
                
                if (!isset($configs['minWidth']) || !isset($configs['minWidth'])){
                    throw new \InvalidArgumentException('configs:minWidth or configs:minHeight is missing.');
                }
                
                $configs['upload_url'] = $router->generate('neutron_form_media_image_upload');
                $configs['crop_url'] = $router->generate('neutron_form_media_image_crop');
                $configs['rotate_url'] = $router->generate('neutron_form_media_image_rotate');
                $configs['reset_url'] = $router->generate('neutron_form_media_image_reset');
                $configs['dir'] = '/' . $defaultOptions['temporary_dir'] . '/';
                
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
        return 'neutron_image_upload';
    }
}