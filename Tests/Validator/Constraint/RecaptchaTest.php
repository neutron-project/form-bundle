<?php
namespace Neutron\FormBundle\Tests\Validator\Constraint;

use Neutron\FormBundle\Validator\Constraint\Recaptcha;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class RecaptchaTest extends BaseTestCase
{
    
    public function testDefault()
    {
        $constraint = new Recaptcha(array('emptyMessage' => 'empty', 'invalidMessage' => 'invalid'));
        
        $this->assertSame('empty', $constraint->emptyMessage);
        $this->assertSame('invalid', $constraint->invalidMessage);
        $this->assertSame('neutron_form_recaptcha_validator', $constraint->validatedBy());        
    }

}