<?php
namespace Neutron\FormBundle\Tests\Command;

use AppBundle\Entity\TradeInChannelCost;

use AppBundle\Entity\TradeInChannel;

use AppBundle\Entity\OrganizationCost;

use AppBundle\Entity\DateTimeSubtractor;

use AppBundle\Entity\Quality;

use AppBundle\Entity\Product;

use Neutron\ComponentBundle\Test\Tool\BaseTestCaseORM;

class SamarcoTest extends BaseTestCaseORM
{
    protected function setUp()
    {
        $this->createMockEntityManager();
    }
    
    
    public function testDefault()
    {
        $this->populateProduct();
        $this->populateOrganizationCost();
        $this->populateTradeInChannel();
        $this->em->flush();
        $this->em->clear();
        
        $product = $this->em->find('AppBundle\Entity\Product', 1);
        $this->assertNotNull($product);
        
        $quality = $this->em->find('AppBundle\Entity\Quality', 1);
        $this->assertNotNull($quality);
        
        $tradeInChannel = $this->em->find('AppBundle\Entity\TradeInChannel', 2);
        $this->assertNotNull($tradeInChannel);
        
        $dateTime = new \DateTime('2012-12-25');
        
        $productRepo = $this->em->getRepository('AppBundle\Entity\Product');
        $price = $productRepo->getSubtractedPrice($product, $quality, $tradeInChannel, $dateTime);
        
        $this->assertEquals(64.0, $price);
    }
    
    protected function populateProduct()
    {
        $product = new Product();
        $product->setName('Samsung Note 2');
        $product->setPrice(100);
        
        $qualityA = new Quality();
        $qualityA->setName('qualityA');
        $qualityA->setSumToSubtract(10);
        
        $qualityB = new Quality();
        $qualityB->setName('qualityB');
        $qualityB->setSumToSubtract(20);
        
        $qualityC = new Quality();
        $qualityC->setName('qualityC');
        $qualityC->setSumToSubtract(30);
        
        $qualityD = new Quality();
        $qualityD->setName('qualityD');
        $qualityD->setSumToSubtract(40);
        
        $dateTimeSubtractor = new DateTimeSubtractor();
        $dateTimeSubtractor->setName('date_time_1');
        $dateTimeSubtractor->setSumToSubtract(11);
        $dateTimeSubtractor->setDateStart(new \DateTime('2012-12-01'));
   
        $product->addQuality($qualityA);
        $product->addQuality($qualityB);
        $product->addQuality($qualityC);
        $product->addQuality($qualityD);
        
        $product->addDateTimeSubtractor($dateTimeSubtractor);
        
        $this->em->persist($product);

    }
    
    protected function populateOrganizationCost()
    {
        $cost1 = new OrganizationCost();
        $cost1->setName('organization_cost_1');
        $cost1->setSumToSubtract(5);
        $this->em->persist($cost1);
        
        $cost2 = new OrganizationCost();
        $cost2->setName('organization_cost_2');
        $cost2->setSumToSubtract(5);
        $this->em->persist($cost2);
  
    }
    
    protected function populateTradeInChannel()
    {
        $tradeInChannel1 = new TradeInChannel();
        $tradeInChannel1->setName('Madrid');
        
        $madridCost1 = new TradeInChannelCost();
        $madridCost1->setName('madrid_cost_1');
        $madridCost1->setsumToSubTract(12);
        
        $madridCost2 = new TradeInChannelCost();
        $madridCost2->setName('madrid_cost_2');
        $madridCost2->setsumToSubTract(7);
        
        $tradeInChannel1->addCost($madridCost1);
        $tradeInChannel1->addCost($madridCost2);
        $this->em->persist($tradeInChannel1);
        
        $tradeInChannel2 = new TradeInChannel();
        $tradeInChannel2->setName('Barsa');
        
        $barsaCost1 = new TradeInChannelCost();
        $barsaCost1->setName('barsa_cost_1');
        $barsaCost1->setsumToSubTract(3);
        
        $barsaCost2 = new TradeInChannelCost();
        $barsaCost2->setName('barsa_cost_2');
        $barsaCost2->setsumToSubTract(2);
        
        $tradeInChannel2->addCost($barsaCost1);
        $tradeInChannel2->addCost($barsaCost2);
        $this->em->persist($tradeInChannel2);

    }
    
    protected function getUsedEntityFixtures()
    {
        return array(
            'AppBundle\Entity\Product', 
            'AppBundle\Entity\Quality', 
            'AppBundle\Entity\DateTimeSubtractor', 
            'AppBundle\Entity\OrganizationCost', 
            'AppBundle\Entity\TradeInChannel',
            'AppBundle\Entity\TradeInChannelCost',     
        );
    } 
}