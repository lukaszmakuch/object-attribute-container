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

/**
 * Allows to associate some attribute and its optional value with some object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ObjectAttributeContainer
{
    /**
     * Associates given attributes with the given object.
     * 
     * When called multiply times, new values override old values stored
     * under identical keys.
     * 
     * @param mixed $object used as a key
     * @param array $attributesWithOptionalValues like
     * [
     *     String "attribute_name" => mixed "attribute_value",
     *     ...
     * ]
     * 
     * @throws ImpossibleToAddAttributes
     * @return mixed exactly the same object which was passed as the $object parameter
     */
    public function addObjAttrs($object, array $attributesWithOptionalValues);
    
    /**
     * Checks whether the given object has an attribute with the given name.
     * 
     * @param mixed $object
     * @param String $attributeName
     * 
     * @return boolean true if there's any (even with null value) 
     * attribute with the given name associated with this object; 
     * If no attribute has been ever associated with this object
     * false is returned
     */
    public function objHasAttr($object, $attributeName);
    
    /**
     * Gets the value of a previously added attribute.
     * 
     * @param mixed $object
     * @param String $attributeName
     * 
     * @return mixed attribute value 
     * @throws AttributeNotFound
     */
    public function getObjAttrVal($object, $attributeName);
    
    /**
     * Removes associated attribute.
     * 
     * @param mixed $object
     * @param String $attributeName
     * 
     * @return null
     * @throws AttributeNotFound
     */
    public function remObjAttr($object, $attributeName);
}
