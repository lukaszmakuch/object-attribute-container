<?php

/**
 * This file is part of the ObjectAttributeContainer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectAttributeContainer;

use lukaszmakuch\ObjectAttributeContainer\Exception\AttributeNotFound;
use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;

class ObjectAttributeContainerImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectAttributeContainer
     */
    private $attrContainer;
    private $testObj;
    
    protected function setUp()
    {
        $this->attrContainer = new ObjectAttributeContainerImpl();
        $this->testObj = new \stdClass();
    }
    
    public function testHoldingAttrs()
    {
        $this->attrContainer->addObjAttrs(
            $this->testObj, 
            ["attr-key" => "attr-val"]
        );
        
        $this->assertTrue($this->attrContainer->objHasAttr(
            $this->testObj,
            "attr-key"
        ));
        
        $this->assertEquals(
            "attr-val",
            $this->attrContainer->getObjAttrVal($this->testObj, "attr-key")
        );
    }
    
    public function testAddingMoreAttrs()
    {
        $this->attrContainer->addObjAttrs(
            $this->testObj, 
            ["attr-key" => "attr-val"]
        );
        
        $this->assertTrue($this->attrContainer->objHasAttr(
            $this->testObj, 
            "attr-key"
        ));
        
        $this->assertEquals(
            "attr-val", 
            $this->attrContainer->getObjAttrVal($this->testObj, "attr-key")
        );
    }
    
    public function testAddingAttrs()
    {
        $this->attrContainer->addObjAttrs(
            $this->testObj, 
            ["attr1-key" => "attr1-val"]
        );
        
        $this->attrContainer->addObjAttrs(
            $this->testObj, 
            ["attr2-key" => "attr2-val", "attr3-key" => null]
        );
        
        $this->assertTrue($this->attrContainer->objHasAttr(
            $this->testObj, 
            "attr1-key"
        ));
        $this->assertEquals(
            "attr1-val", 
            $this->attrContainer->getObjAttrVal($this->testObj, "attr1-key")
        );
        
        $this->assertTrue($this->attrContainer->objHasAttr(
            $this->testObj, 
            "attr2-key"
        ));
        $this->assertEquals(
            "attr2-val", 
            $this->attrContainer->getObjAttrVal($this->testObj, "attr2-key")
        );
        
        $this->assertTrue($this->attrContainer->objHasAttr(
            $this->testObj, 
            "attr3-key"
        ));
        $this->assertEquals(
            null, 
            $this->attrContainer->getObjAttrVal($this->testObj, "attr3-key")
        );
    }
    
    public function testRemovingAttrs()
    {
        $this->attrContainer->addObjAttrs(
            $this->testObj, 
            ["attr-key" => "attr-val"]
        );
        
        $this->attrContainer->remObjAttr(
            $this->testObj,
            "attr-key"
        );
        
        $this->assertFalse($this->attrContainer->objHasAttr(
            $this->testObj, 
            "attr-key"
        ));
    }
    
    public function testExceptionWhenAccessingNonexistentAttr()
    {
        $this->setExpectedException(AttributeNotFound::class);
        $this->attrContainer->getObjAttrVal(
            $this->testObj, 
            "nonexistent-attr-key"
        );
    }
    
    public function testExceptionWhenRemovingNonexistentAttr()
    {
        $this->setExpectedException(AttributeNotFound::class);
        $this->attrContainer->remObjAttr(
            $this->testObj, 
            "nonexistent-attr-key"
        );
    }
}
