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

/**
 * Represents a data table cell.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Cell
{
    /**
     * @var array
     */
    private $groups;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     * @param array $groups
     */
    public function __construct($value, array $groups = array())
    {
        $this->value = $value;
        $this->groups = $groups;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set the value for this cell.
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Return this cell instances value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
