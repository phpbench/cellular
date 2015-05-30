<?php

namespace DTL\DataTable\Exception;

class InvalidCollectionTypeException extends \InvalidArgumentException
{
    public function __construct($instance, $type)
    {
        parent::__construct(sprintf(
            '"%s" instances can only accept elements of type Cell, got "%s"',
            get_class($instance), is_object($type) ? get_class($type) : gettype($type)
        ));
    }
}
