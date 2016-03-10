<?php

/**
 * This file is part of the ObjectAttributeContainer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectAttributeContainer\Impl;

use lukaszmakuch\ObjectAttributeContainer\Exception\AttributeNotFound;
use lukaszmakuch\ObjectAttributeContainer\Exception\ImpossibleToAddAttributes;
use lukaszmakuch\ObjectAttributeContainer\Exception\ImpossibleToRegisterContainer;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use SplObjectStorage;
use function mb_strpos;

/**
 * Allows to redirect actual actions to other containers 
 * based on prefixes of attributes.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ObjectAttributeContainerProxy implements ObjectAttributeContainer
{
    private $containersByPrefixes = [];
    private $prefixesByContainers;
    
    public function __construct()
    {
        $this->prefixesByContainers = new SplObjectStorage();
    }
    
    /**
     * @param String $supportedAttributePrefix
     * @param ObjectAttributeContainer $actualContainer
     * 
     * @throws ImpossibleToRegisterContainer
     * @return null
     */
    public function registerContainer(
        $supportedAttributePrefix,
        ObjectAttributeContainer $actualContainer
    ) {
        if (isset($this->containersByPrefixes[$supportedAttributePrefix])) {
            throw new ImpossibleToRegisterContainer();
        }
        
        $this->containersByPrefixes[$supportedAttributePrefix] = $actualContainer;
        uksort($this->containersByPrefixes, function ($prefix1, $prefix2) {
            $prefix1Length = mb_strlen($prefix1);
            $prefix2Length = mb_strlen($prefix2);
            if ($prefix1Length > $prefix2Length) {
                return -1;
            } elseif ($prefix1Length < $prefix2Length) {
                return 1;
            } else {
                return 0;
            }
        });
        $this->prefixesByContainers->offsetSet($actualContainer, $supportedAttributePrefix);
    }
    
    public function addObjAttrs($object, array $attributesWithOptionalValues)
    {
        foreach ($attributesWithOptionalValues as $attrName => $attrVal) {
            $suitableContainer = $this->getContainerBy($attrName);
            if (is_null($suitableContainer)) {
                throw new ImpossibleToAddAttributes();
            }
            
            $suitableContainer->addObjAttrs(
                $object,
                [$this->getPrefixFreeVersionOf($attrName) => $attrVal]
            );
        }
        
        return $object;
    }

    public function getObjAttrVal($object, $attributeName)
    {
        $suitableContainer = $this->getContainerBy($attributeName);
        if (is_null($suitableContainer)) {
            throw new AttributeNotFound();
        }

        return $suitableContainer->getObjAttrVal(
            $object, 
            $this->getPrefixFreeVersionOf($attributeName)
        );
    }

    public function objHasAttr($object, $attributeName)
    {
        $suitableContainer = $this->getContainerBy($attributeName);
        return $suitableContainer->objHasAttr(
            $object, 
            $this->getPrefixFreeVersionOf($attributeName)
        );
    }

    public function remObjAttr($object, $attributeName)
    {
        $suitableContainer = $this->getContainerBy($attributeName);
        if (is_null($suitableContainer)) {
            throw new AttributeNotFound();
        }

        return $suitableContainer->remObjAttr(
            $object, 
            $this->getPrefixFreeVersionOf($attributeName)
        );
    }
    
    /**
     * @param String $attrName
     * @return ObjectAttributeContainer|null
     */
    private function getContainerBy($attrName)
    {
        foreach ($this->containersByPrefixes as $prefix => $container) {
            if ($this->isPrefixOfAttr($prefix, $attrName)) {
                return $container;
            }
        }
        
        return null;
    }
    
    /**
     * @param String $attributeName
     * @return String
     */
    private function getPrefixFreeVersionOf($attributeName)
    {
        $suitableContainer = $this->getContainerBy($attributeName);
        $prefix = $this->getPrefixOfContainer($suitableContainer);
        return $this->removePrefixFromAttr($prefix, $attributeName);
    }
    
    /**
     * @param ObjectAttributeContainer $registeredContainer
     * @return String
     */
    private function getPrefixOfContainer(ObjectAttributeContainer $registeredContainer)
    {
        return $this->prefixesByContainers->offsetGet($registeredContainer);
    }
    
    /**
     * @param String $prefix
     * @param String $attrName
     * @return boolean
     */
    private function isPrefixOfAttr($prefix, $attrName)
    {
        return (0 === mb_strpos($attrName, $prefix));
    }
    
    /**
     * @param String $prefix like "prefix"
     * @param String $attrName like "prefixparam"
     * @return String like "param"
     */
    private function removePrefixFromAttr($prefix, $attrName)
    {
        return preg_replace("@^" . preg_quote($prefix, "@") . "@u", "", $attrName);
    }
}
