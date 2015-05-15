<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\DataTable;

class Cell implements AggregateableInterface
{
    private $groups;
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
     * Return true if the cell is in the given group.
     *
     * @param mixed $group
     *
     * @return bool
     */
    public function inGroup($group)
    {
        return in_array($group, $this->groups);
    }

    public function inGroups(array $groups)
    {
        foreach ($groups as $group) {
            if ($this->inGroup($group)) {
                return true;
            }
        }
    }

    /**
     * Return this cell instances value.
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function sum(array $groups = array())
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function min(array $groups = array())
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function max(array $groups = array())
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function avg(array $groups = array())
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function median(array $groups = array(), $ceil = false)
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function values(array $groups = array())
    {
        return array($this->value);
    }
}
