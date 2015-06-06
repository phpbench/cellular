<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Cellular;

trait AttributeTrait
{
    private $attributes = array();

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Attribute with name "%s" does not exist. Known attributes: "%s"',
                $name, implode('", "', $this->attributes)
            ));
        }

        return $this->attributes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}
