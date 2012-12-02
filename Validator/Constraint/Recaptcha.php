<?php
/*
 * This file is part of NeutronDataGridBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Recaptcha extends Constraint
{
    /**
     * @var string
     */
    public $emptyMessage = 'validator.captcha.empty';
    
    /**
     * @var string
     */
    public $invalidMessage = 'validator.captcha.invalid';
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Validator\Constraint::validatedBy()
     */
    public function validatedBy()
    {
        return 'neutron_form_recaptcha_validator';
    }
    
}