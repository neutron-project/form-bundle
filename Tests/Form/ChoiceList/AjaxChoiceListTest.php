<?php
namespace Neutron\FormBundle\Tests\Form\ChoiceList;

use Neutron\FormBundle\Form\ChoiceList\AjaxChoiceList;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class AjaxChoiceListTest extends BaseTestCase
{
    public function testDefault()
    {
        $choiceList = new AjaxChoiceList(array());
        
        $this->assertSame(array('value'), $choiceList->getValuesForChoices(array('value')));
        
        $this->assertSame(array('label'), $choiceList->getChoicesForValues(array('label')));
    }
}