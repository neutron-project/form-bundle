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

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates multi file upload element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class MultiFileUploadType extends AbstractType
{

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
        $builder->add('position', 'hidden');
        $builder->add('enabled', 'hidden');
        $builder->add('currentVersion', 'hidden');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_multi_file_upload';
    }

}