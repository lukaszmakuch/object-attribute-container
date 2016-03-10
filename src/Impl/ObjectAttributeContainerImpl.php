<?php

/**
 * This file is part of the ObjectAttributeContainer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectAttributeContainer\Impl;

use lukaszmakuch\ObjectAttributeContainer\Exception\AttributeNotFound;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use SplObjectStorage;

/**
 * Default implementation of the attribute container.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ObjectAttributeContainerImpl implements ObjectAttributeContainer
{
    /**
     * @var SplObjectStorage
     */
    private $attributesByObjects;

    public function __construct()
    {
        $this->attributesByObjects = new SplObjectStorage();
    }
    
    public function addObjAttrs($object, array $attributesWithOptionalValues)
    {
        $newAttrs = array_merge(
            $this->getAllAttributesOf($object), 
            $attributesWithOptionalValues
        );
        $this->attributesByObjects->offsetSet($object, $newAttrs);
        return $object;
    }

    public function getObjAttrVal($object, $attributeName)
    {
        if (!$this->objHasAttr($object, $attributeName)) {
            throw new AttributeNotFound();
        }
        
        return $this->getAllAttributesOf($object)[$attributeName];
    }

    public function objHasAttr($object, $attributeName)
    {
        return array_key_exists(
            $attributeName, 
            $this->getAllAttributesOf($object)
        );
    }

    public function remObjAttr($object, $attributeName)
    {
        if (!$this->objHasAttr($object, $attributeName)) {
            throw new AttributeNotFound();
        }

        $allAttrs = $this->getAllAttributesOf($object);
        unset($allAttrs[$attributeName]);
        $this->attributesByObjects->offsetSet($object, $allAttrs);
    }
    
    /**
     * @param mixed $object
     * @return boolean
     */
    private function somethingHasBeenAssignedTo($object)
    {
        return $this->attributesByObjects->offsetExists($object);
    }
    
    /**
     * @param mixed $object
     * @return array like ["attrWithValue" => mixed, ...]
     */
    private function getAllAttributesOf($object)
    {
        return $this->somethingHasBeenAssignedTo($object)
            ? $this->attributesByObjects->offsetGet($object)
            : [];
    }
}
