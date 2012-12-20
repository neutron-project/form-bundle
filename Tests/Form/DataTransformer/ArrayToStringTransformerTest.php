<?php
namespace Neutron\FormBundle\Tests\Form\DataTransformer;

use Neutron\FormBundle\Form\DataTransformer\ArrayToStringTransformer;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class ArrayToStringTransformerTest extends BaseTestCase
{
    public function testTransform()
    {
        $transformer = new ArrayToStringTransformer();
        
        $this->assertNull($transformer->transform(''));
        $this->assertSame('one,two', $transformer->transform(array('one', 'two')));
    }
    
    public function testReserveTransform()
    {
        $transformer = new ArrayToStringTransformer();
       
        $this->assertSame(array('one', 'two'), $transformer->reverseTransform(array('one', 'two')));
        $this->assertSame(array('one', 'two'), $transformer->reverseTransform('one,two'));

    }
}