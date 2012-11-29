<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Bundle\FormBundle\Form\Type;


use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery date range picker element
 *
 * @author Nikolay Georgiev <nikolay.georgiev@zend.bg>
 * @since 1.0
 */
class DateRangePickerType extends AbstractType
{

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $els = $builder->getAttribute('property_path')->getElements();
        $name = $els[0];
        $start = $name . '_daterange_start';
        $end = $name . '_daterange_end';

        // Overwrite required option for child fields
        $options['first_options']['configs']['range_id']  = $start;
        $options['first_options']['configs']['range']['start']  = $start;
        $options['first_options']['configs']['range']['end']  = $end;

        $options['second_options']['configs']['range_id']  = $end;
        $options['second_options']['configs']['range']['start']  = $start;
        $options['second_options']['configs']['range']['end']  = $end;

        $builder
            ->add($options['first_name'], 'neutron_datepicker', array_merge($options['options'], $options['first_options']))
            ->add($options['second_name'], 'neutron_datepicker', array_merge($options['options'], $options['second_options']))
        ;

    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
            'options'        => array(),
            'first_options'  => array(),
            'second_options' => array(),
            'first_name'     => 'first_date',
            'second_name'    => 'second_date',
            'error_bubbling' => false,
        ));
    
    }


    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_daterangepicker';
    }


}