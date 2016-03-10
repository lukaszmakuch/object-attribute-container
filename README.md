# ObjectAttributeContainer
Allows to associate attributes with objects.

## Getting package
```
$ composer require lukaszmakuch/object-attribute-container
```
## Working with the object attribute container

```php
<?php

use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use lukaszmakuch\ObjectAttributeContainer\Exception\AttributeNotFound;
use lukaszmakuch\ObjectAttributeContainer\Exception\ImpossibleToAddAttributes;

/* @var $attrContainer \ObjectAttributeContainer */

//create a test object
$obj = new \stdClass();

//associate parameters with the object
try {
    $attrContainer->addObjAttrs(
        $obj,
        ["attr-key" => "attr-val"]
    );
} catch (ImpossibleToAddAttributes $e) {
    //...
}

//check if there's a parameter with the given name
$attrContainer->objHasAttr($obj, "attr-key"); //true

//get that parameter value
try {
    $attrContainer->getObjAttrVal($obj, "attr-key"); //attr-val
} catch (AttributeNotFound $e) {
    //...
}

//remove that parameter
try {
    $attrContainer->remObjAttr($obj, "attr-key");
} catch (AttributeNotFound $e) {
    //...
}
```

## Getting an instance of the container
```php
<?php

use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerProxy;

//default
$container = new ObjectAttributeContainerImpl();

//proxy
$containerProxy = new ObjectAttributeContainerProxy();
```

## More information
For more details check interfaces and test cases.
