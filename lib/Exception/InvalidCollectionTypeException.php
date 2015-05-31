<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Cellular\Exception;

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
