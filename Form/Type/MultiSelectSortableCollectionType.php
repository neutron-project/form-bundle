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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Neutron\DataGridBundle\DataGrid\DataGridInterface;

use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery multi select sortable element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class MultiSelectSortableCollectionType extends AbstractType
{
    
    /**
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected $eventSubscriber;
    
    /**
     * Construct
     * 
     * @param EventSubscriberInterface $eventSubscriber
     */
    public function __construct(EventSubscriberInterface $eventSubscriber)
    {
        $this->eventSubscriber = $eventSubscriber;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['grid'] instanceof DataGridInterface){
            throw new \InvalidArgumentException('Option grid must be instance of DataGridInterface.');
        } elseif(!$options['grid']->isMultiSelectSortableEnabled()){
            throw new \InvalidArgumentException(
                sprintf('Option multiSelectSortable in DataGrid "%s" is not enabled.', $options['grid']->getName())
            );
        } elseif (!is_string($options['grid']->getMultiSelectSortableColumn())){
            throw new \InvalidArgumentException(
                sprintf('Option multiSelectSortableColumn in DataGrid "%s" is not set.', $options['grid']->getName())
            );
        }
        
        $builder->addEventSubscriber($this->eventSubscriber);
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildView()
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['grid'] = $options['grid'];
        $view->vars['inversed_property'] = $options['options']['inversed_property'];
        $view->vars['configs'] = $options['configs'];
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultConfigs = array();
        
        $resolver->setDefaults(array(
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
            'type' => 'neutron_multi_select_sortable',
            'translation_domain' => 'NeutronFormBundle',
            'configs' => $defaultConfigs,
        ));
        
        $resolver->setNormalizers(array(
            'configs' => function (Options $options, $value) use ($defaultConfigs){
                $configs = array_replace_recursive($defaultConfigs, $value);
                return $configs;
            }
        ));
          
        $resolver->setRequired(array('grid'));
        
        $resolver->setAllowedTypes(array('grid' => array('object')));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::getParent()
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_multi_select_sortable_collection';
    }

}