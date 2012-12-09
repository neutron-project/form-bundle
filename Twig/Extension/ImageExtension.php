<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Twig\Extension;

use Symfony\Component\Form\FormView;

use Symfony\Component\DependencyInjection\Container;

use Neutron\FormBundle\Model\ImageInterface;

/**
 * Twig extension
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImageExtension extends \Twig_Extension
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * Construct
     * 
     * @param Container $container
     */
    public function __construct(Container $container)
    {
       $this->container = $container;
    }
    
    /**
     * Reorders form children
     * 
     * @param FormView $form
     * @return FormView
     */
    public function reorderMultiImageForm(FormView$form)
    {
        $children = array();
        
        foreach ($form as $child){
            $position = (int) $child->getChild('position')->vars['value'];
            $children[$position] = $child;
        }
        
        ksort($children);
        $form->setChildren($children);
        
        return $form;
    }

    /**
     * @param ImageInterface $entity
     */
    public function outputImage(ImageInterface $image, $filter, array $options = array())
    {
        return
           $this->container->get('templating')
                ->render('NeutronFormBundle:Form:image.html.twig',
                   array('image' => $image, 'filter' => $filter, 'options' => $options)
                );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'neutron_image' => new \Twig_Function_Method($this, 'outputImage', array('is_safe' => array('html'))),
            'neutron_multi_image_form_reorder' => new \Twig_Function_Method($this, 'reorderMultiImageForm')
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'neutron_image';
    }

}
