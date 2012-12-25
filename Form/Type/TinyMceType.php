<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c)  Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\Type;

use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Neutron\FormBundle\Security\Handler\TinyMceSecurityHandler;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery tinymce element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class TinyMceType extends AbstractType
{    
    /**
     * @var \Neutron\Bundle\FormBundle\Security\Handler\TinyMceSecurityHandler
     */
    protected $security;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Construct
     * 
     * @param TinyMceSecurityHandler $security
     * @param array $options
     */
    public function __construct(TinyMceSecurityHandler $security, array $options)
    {
        $this->security = $security;
        $this->options = $options;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->security->authorize($options['security']);
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildView()
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {   
        $view->vars['configs'] = $options['configs'];
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $defaultConfigs = $this->options;
        
        $resolver->setDefaults(array(
            'translation_domain' => 'NeutronFormBundle',
            'security' => $defaultConfigs['security'],
            'configs' => $defaultConfigs
        ));
        
        $resolver->setNormalizers(array(
            'configs' => function (Options $options, $value) use ($defaultConfigs){
                $configs = array_replace_recursive($defaultConfigs, $value);
    
                return $configs;
            }
        ));
        
        $resolver->setAllowedTypes(array(
            'security' => 'array'
        ));
    }  

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::getParent()
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_tinymce';
    }

}