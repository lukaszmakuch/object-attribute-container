<?php

/**
 * This file is part of the ObjectAttributeContainer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectAttributeContainer;

use lukaszmakuch\ObjectAttributeContainer\Exception\AttributeNotFound;
use lukaszmakuch\ObjectAttributeContainer\Exception\ImpossibleToAddAttributes;
use lukaszmakuch\ObjectAttributeContainer\Exception\ImpossibleToRegisterContainer;
use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerProxy;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use PHPUnit_Framework_TestCase;
use stdClass;

class ObjectAttributeContainerProxyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectAttributeContainerProxy
     */
    private $proxy;
    
    /**
     * @var ObjectAttributeContainer
     */
    private $actualContainerA;
    
    /**
     * @var ObjectAttributeContainer
     */
    private $actualContainerB;
    
    private $testObj;
    
    protected function setUp()
    {
        $this->actualContainerA = new ObjectAttributeContainerImpl();
        $this->actualContainerB = new ObjectAttributeContainerImpl();
        $this->proxy = new ObjectAttributeContainerProxy();
        $this->testObj = new stdClass();
    }
    
    public function testTasksDelegation()
    {
        $this->proxy->registerContainer("a", $this->actualContainerA);
        $this->proxy->registerContainer("b", $this->actualContainerB);
        
        $this->proxy->addObjAttrs($this->testObj, ["akey" => "aval"]);
        
        $this->assertTrue($this->proxy->objHasAttr($this->testObj, "akey"));
        $this->assertEquals("aval", $this->proxy->getObjAttrVal($this->testObj, "akey"));
        $this->assertTrue($this->actualContainerA->objHasAttr($this->testObj, "key"));
        $this->assertEquals("aval", $this->actualContainerA->getObjAttrVal($this->testObj, "key"));
        
        $this->proxy->remObjAttr($this->testObj, "akey");
        $this->assertFalse($this->proxy->objHasAttr($this->testObj, "akey"));
        $this->assertFalse($this->actualContainerA->objHasAttr($this->testObj, "key"));
    }
    
    public function testExceptionWhenAddingWithUnsupportedPrefix()
    {
        $this->setExpectedException(ImpossibleToAddAttributes::class);
        $this->proxy->addObjAttrs($this->testObj, ["unsupported-key" => "its-val"]);
    }
    
    public function testExceptionWhenReadingFromUnsupportedPrefix()
    {
        $this->setExpectedException(AttributeNotFound::class);
        $this->proxy->getObjAttrVal($this->testObj, "unsupported-key");
    }
    
    public function testExceptionWhenRemovingFromUnsupportedPrefix()
    {
        $this->setExpectedException(AttributeNotFound::class);
        $this->proxy->remObjAttr($this->testObj, "unsupported-key");
    }
    
    public function testExceptionWhenPrefixConflict()
    {
        $this->setExpectedException(ImpossibleToRegisterContainer::class);
        $this->proxy->registerContainer("a", $this->actualContainerA);
        $this->proxy->registerContainer("a", $this->actualContainerB);
    }
    
    public function testSimilarPrefixes()
    {
        $this->proxy->registerContainer("a", $this->actualContainerA);
        $this->proxy->registerContainer("ab", $this->actualContainerB);
        
        $this->proxy->addObjAttrs($this->testObj, ["abkey" => "val"]);
        
        $this->assertTrue($this->proxy->objHasAttr($this->testObj, "abkey"));
        $this->assertEquals("val", $this->proxy->getObjAttrVal($this->testObj, "abkey"));
        $this->assertEquals("val", $this->actualContainerB->getObjAttrVal($this->testObj, "key"));
    }
    
    public function testReturnedValue()
    {
        $this->proxy->registerContainer("a", $this->actualContainerA);
        $this->assertTrue(
            $this->proxy->addObjAttrs($this->testObj, ["a" => "v"])
            ===
            $this->testObj
        );
    }
}
