<?php

use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;

/**
 * This file is part of the ObjectAttributeContainer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

class ObjectAttributeContainerImplTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectAttributeContainer
     */
    private $attrContainer;
    
    protected function setUp()
    {
        $this->attrContainer = new ObjectAttributeContainerImpl();
    }
}
