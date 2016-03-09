<?php

/**
 * This file is part of the ObjectAttributeContainer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectAttributeContainer\Exception;

/**
 * Thrown when trying to access an attribute which doesn't exist.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AttributeNotFound extends \RuntimeException
{
}