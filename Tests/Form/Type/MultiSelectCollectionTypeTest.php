<?php
namespace Neutron\FormBundle\Tests\Form\Type;


use Neutron\FormBundle\Form\Type\MultiSelectType;

use Neutron\FormBundle\Form\Type\MultiSelectCollectionType;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class MultiSelectCollectionTypeTest extends TypeTestCase
{

    protected function setUp()
    {   
        parent::setUp();
        
        if (!interface_exists('Neutron\DataGridBundle\DataGrid\DataGridInterface')) {
            $this->markTestSkipped('DataGridBundle is not available');
        }
    }
    
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('neutron_multi_select_collection', null, array(
            'grid' => $this->createMockDataGrid(),
            'options' => array(
                'class' => 'Neutron\FormBundle\Tests\Fixture\Entity\Project'        
            ),
        ));
        
        $view = $form->createView();
        $configs = $view->vars['configs'];
        $this->assertSame(array(), $configs);
    }
    
    public function testWithInvalidDataGrid()
    {
        $this->setExpectedException('InvalidArgumentException');
        $form = $this->factory->create('neutron_multi_select_collection', null, array(
            'grid' => new \stdClass(),      
        ));
        
        $form->createView();
    }
    
    public function testWithInvalidMethodIsMultiSelectEnabled()
    {
        $this->setExpectedException('InvalidArgumentException');
        $form = $this->factory->create('neutron_multi_select_collection', null, array(
            'grid' => $this->createMockDataGrid(false),      
        ));
        
        $form->createView();
    }
    
    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(
			        new MultiSelectCollectionType(),
				    new MultiSelectType($this->createMockTransformer())
		        )
			)
    	);
    }
    
    protected function createMockTransformer()
    {
        $mock = $this
            ->getMockBuilder('Neutron\FormBundle\Form\DataTransformer\DoctrineORMTransformer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $mock
            ->expects($this->any())
            ->method('setClass')
            ->with('Neutron\FormBundle\Tests\Fixture\Entity\Project')
        ;
    
        return $mock;
    }
    
    protected function createMockDataGrid($multiSelectSortableEnabled = true)
    {
        $mock = $this->getMock('Neutron\DataGridBundle\DataGrid\DataGridInterface');;
    
        $mock
            ->expects($this->any())
            ->method('isMultiSelectEnabled')
            ->will($this->returnValue($multiSelectSortableEnabled))
        ;

        return $mock;
    }
}