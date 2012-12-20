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

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\FormView;

use Symfony\Component\Form\DataTransformerInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormViewInterface;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates plain element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class PlainType extends AbstractType
{
    
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    
    /**
     * Construct
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildView()
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {   
        $view->vars['value'] = $this->transform($form->getViewData(), $options);
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    { 
        $resolver->setDefaults(array(
            'translation_domain' => 'NeutronFormBundle',
            'date_format' => \IntlDateFormatter::LONG,
            'date_pattern' => null,
            'time_format' => \IntlDateFormatter::MEDIUM,
        ));
        
        $resolver->setNormalizers(array(
            'read_only' => function (Options $options, $value) {
                return true;
            }
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::getParent()
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_plain';
    }
    
    /**
     * Converts view value to string
     * 
     * @param mixed $value
     * @param array $options
     * @return string
     */
    private function transform($value, array $options)
    {   
        if (empty($value)) {
            $value = '-----';
        } elseif (is_array($value)) {
            $value = implode(', ', $value);
        } elseif ($value instanceof \DateTime) {
            $formatter  = new \IntlDateFormatter(
                $this->request->getLocale(),
                $options['date_format'],
                $options['time_format'],
                null,
                \IntlDateFormatter::GREGORIAN,
                $options['date_pattern']
            );
            $formatter->setLenient(false);
            $value = $formatter->format($value);
        } elseif (is_object($value)) {
            if (method_exists($value, '__toString')) {
                $value = $value->__toString();
            } else {
                $value = get_class($value);
            }
        }
        
        return (string) $value;
    }

}