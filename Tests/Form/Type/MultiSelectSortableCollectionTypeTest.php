<?php
namespace Neutron\FormBundle\Tests\Form\Type;

use Neutron\FormBundle\Form\Type\InversedType;

use Neutron\FormBundle\Form\Type\MultiSelectSortableType;

use Neutron\FormBundle\Form\Type\MultiSelectSortableCollectionType;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

use Neutron\FormBundle\Tests\Form\Extension\TypeExtensionTest;

class MultiSelectSortableCollectionTypeTest extends TypeTestCase
{
    public function setUp()
    {
        parent::setUp();
        
        if (!interface_exists('Neutron\DataGridBundle\DataGrid\DataGridInterface')) {
            $this->markTestSkipped('DataGridBundle is not available');
        }
    } 

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('neutron_multi_select_sortable_collection', null, array(
            'grid' => $this->createMockDataGrid(),
            'options' => array(
                'data_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\ProjectReference',
                'inversed_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\Project',
                'inversed_property' => 'project',
            )
        ));
        $view = $form->createView();
        $configs = $view->vars['configs'];
        $this->assertSame(array(), $configs);
    }
    
    public function testWithInvalidDataGrid()
    {
        $this->setExpectedException('InvalidArgumentException');
        $form = $this->factory->create('neutron_multi_select_sortable_collection', null, array(
            'grid' => new \stdClass(),
            'options' => array(
                'data_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\ProjectReference',
                'inversed_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\Project',
                'inversed_property' => 'project',
            )
        ));
    }
    
    public function testWithInvalidDataGridIsMultiSelectSortableEnabled()
    {
        $this->setExpectedException('InvalidArgumentException');
        $form = $this->factory->create('neutron_multi_select_sortable_collection', null, array(
            'grid' => $this->createMockDataGrid(false),
            'options' => array(
                'data_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\ProjectReference',
                'inversed_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\Project',
                'inversed_property' => 'project',
            )
        ));
    }
    
    public function testWithInvalidDataGridGetMultiSelectSortableColumn()
    {
        $this->setExpectedException('InvalidArgumentException');
        $form = $this->factory->create('neutron_multi_select_sortable_collection', null, array(
            'grid' => $this->createMockDataGrid(true, null),
            'options' => array(
                'data_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\ProjectReference',
                'inversed_class' => 'Neutron\FormBundle\Tests\Fixture\Entity\Project',
                'inversed_property' => 'project',
            )
        ));
    }

    protected function getExtensions()
    {
    	return array(
			new TypeExtensionTest(
				array(
		            new MultiSelectSortableCollectionType($this->createMockFormEventSubscriber()),
		            new MultiSelectSortableType(),
				    new InversedType($this->createMockTransformer())
		        )
			)
    	);
    }
    
    protected function createMockFormEventSubscriber()
    {
        $mock = $this->getMock('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    
        $mock::staticExpects($this->any())
            ->method('getSubscribedEvents')
            ->will($this->returnValue(array()))
        ;
    
        return $mock;
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
    
    protected function createMockDataGrid($multiSelectSortableEnabled = true, $multiSelectSortableColumn = 'label')
    {
        $mock = $this->getMock('Neutron\DataGridBundle\DataGrid\DataGridInterface');;
    
        $mock
            ->expects($this->any())
            ->method('isMultiSelectSortableEnabled')
            ->will($this->returnValue($multiSelectSortableEnabled))
        ;
    
        $mock
            ->expects($this->any())
            ->method('getMultiSelectSortableColumn')
            ->will($this->returnValue($multiSelectSortableColumn))
        ;
    
        return $mock;
    }
    
    protected function createMockShowCase()
    {
        $mock = $this->getMock('Neutron\FormBundle\Tests\Fixture\Entity\ShowCase');;
    
        $mock
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1))
        ;
    
    
        return $mock;
    }
}