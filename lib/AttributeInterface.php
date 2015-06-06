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

interface AttributeInterface
{
    /**
     * Return the attribute with the given name.
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getAttribute($name);

    /**
     * Return true if the attribute exists.
     *
     * @return bool
     */
    public function hasAttribute($name);

    /**
     * Set the value of a named attribute.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value);

    /**
     * Set all the attributes
     *
     * @param mixed[] $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * Get all attributes
     *
     * @return mixed[]
     */
    public function getAttributes();
}
