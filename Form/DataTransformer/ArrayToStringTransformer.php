<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transforms array to string value
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ArrayToStringTransformer implements DataTransformerInterface
{
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\DataTransformerInterface::transform()
     */
    public function transform($value)
    {   
        if (empty($value) || !is_array($value)) { 
            return null; 
        }

        return implode(',', $value);
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     */
    public function reverseTransform($value)
    {
        if (is_array($value)) {
            return $value;
        } elseif (empty($value)){
            return array();
        }
        
        return explode(',', $value);
    }
}