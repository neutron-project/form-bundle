<?php
namespace Neutron\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PlainTypeViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {   
        if (true === $value) {
            $value = 'true';
        } elseif (false === $value) {
            $value = 'false';
        } elseif (null === $value) {
            $value = '-----'; 
        } elseif (is_array($value)) {
            $value = implode(', ', $value);
        } elseif ($value instanceof \DateTime) { 
            $value = $value->format('Y-m-d H:i:s');
        } elseif (is_object($value)) {
            if (method_exists($value, '__toString')) {
                $value = $value->__toString();
            } else {
                $value = get_class($value);
            }
        }
        
        return $value;
    }
    
    public function reverseTransform($value)
    {  
        if (null === $value || '' === $value) {
            return null;
        }
        
        return $value;
    }
}