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

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\DataTransformerInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates inversed type
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class InversedType extends AbstractType
{
    
    /**
     * @var \Symfony\Component\Form\DataTransformerInterface
     */
    protected $transformer;

    /**
     * Construct
     * 
     * @param DataTransformerInterface $transformer
     */
    public function __construct(DataTransformerInterface $transformer)
    {
    	$this->transformer = $transformer;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformer->setClass($options['inversed_class']);
        $builder->addModelTransformer($this->transformer);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'inversed_class'
        ));
        
        $resolver->setAllowedTypes(array(
            'inversed_class' => array('string'),
        ));
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_inversed';
    }

}