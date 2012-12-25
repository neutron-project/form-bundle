<?php
namespace Neutron\FormBundle\Tests\Form\DataTransformer;

use Neutron\FormBundle\Form\DataTransformer\DoctrineORMTransformer;

use AppBundle\Entity\Project;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

class DoctrineORMTransformerTest extends BaseTestCaseORM
{
    const FIXTURE_PROJECT = 'Neutron\FormBundle\Tests\Fixture\Entity\Project';
    
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    public function testTransformWithNull()
    {
        $transformer = new DoctrineORMTransformer($this->em);
        $transformer->setClass(self::FIXTURE_PROJECT);
        
        $this->assertNull($transformer->transform(null));
    }
    
    public function testTransformWithInvalidValue()
    {
        $transformer = new DoctrineORMTransformer($this->em);
        $transformer->setClass(self::FIXTURE_PROJECT);
        
        $this->setExpectedException('Symfony\Component\Form\Exception\UnexpectedTypeException');
        $transformer->transform('invalid');        
    }
    
    public function testTransform()
    {
        $this->populate();
        $transformer = new DoctrineORMTransformer($this->em);
        $transformer->setClass(self::FIXTURE_PROJECT);
        
        $entity = $this->em->getReference(self::FIXTURE_PROJECT, 1);
      
        $result = $transformer->transform($entity);    
        $this->assertSame(1, $result);
    }
    
    public function testReverveTransformerWithNull()
    {
        $transformer = new DoctrineORMTransformer($this->em);
        $transformer->setClass(self::FIXTURE_PROJECT);
        
        $this->assertNull($transformer->reverseTransform(null));
    }
    
    public function testReverveTransformer()
    {
        $this->populate();
        $this->em->clear();
        
        $transformer = new DoctrineORMTransformer($this->em);
        $transformer->setClass(self::FIXTURE_PROJECT);

        $this->assertInstanceOf('EntityProxy\__CG__\Neutron\FormBundle\Tests\Fixture\Entity\Project', $transformer->reverseTransform(1));
    }
    
    protected function populate()
    {
        $project = new Project();
        $project->setTitle('project');
        $this->em->persist($project);
        $this->em->flush();
    }
    
    protected function getUsedEntityFixtures()
    {
        return array(self::FIXTURE_PROJECT);
    }
    
}