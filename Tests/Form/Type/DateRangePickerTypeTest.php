<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Form\Type\DatePickerType;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

use Neutron\FormBundle\Form\Type\DateRangePickerType;

class DateRangePickerTypeTest extends TypeTestCase
{


    public function testDefault()
    {
        $form = $this->factory->create('neutron_daterangepicker');

        $form->bind(array('first_date' => '2012-12-21', 'second_date' => '2012-12-22'));
        $form->createView();
        $data = $form->getData();

        $this->assertCount(2, $data);
        $this->assertInstanceOf('\DateTime', $data['first_date']);
        $this->assertInstanceOf('\DateTime', $data['second_date']);
    }


    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(
				    new DateRangePickerType(),
				    new DatePickerType()
				)
			)
    	);
    }

}