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

abstract class Aggregated implements AggregateableInterface
{
    /**
     * {@inheritDoc}
     */
    public function sum(array $groups = array())
    {
        return AggregateHelper::sum($this->values($groups));
    }

    /**
     * {@inheritDoc}
     */
    public function min(array $groups = array())
    {
        return AggregateHelper::min($this->values($groups));
    }

    /**
     * {@inheritDoc}
     */
    public function max(array $groups = array())
    {
        return AggregateHelper::max($this->values($groups));
    }

    /**
     * {@inheritDoc}
     */
    public function avg(array $groups = array())
    {
        return AggregateHelper::avg($this->values($groups));
    }

    /**
     * {@inheritDoc}
     */
    public function median(array $groups = array(), $ceil = false)
    {
        return AggregateHelper::median($this->values($groups), $ceil);
    }

    /**
     * {@inheritDoc}
     */
    public function values(array $groups = array()) 
    {
        $values = array();
        foreach ($this->cells($groups) as $column => $cell) {
            $values[$column] = $cell->value();
        }

        return $values;
    }

    /**
     * Fill the table with a value
     *
     * @param mixed $value
     * @param array $groups
     */
    public function fill($value, array $groups = array())
    {
        foreach ($this->getRows() as $row) {
            $row->fill($value, $groups);
        }
    }

    /**
     * Apply a closure to each cell
     */
    public function map(\Closure $closure, array $groups = array())
    {
        foreach ($this->cells($groups) as $cell) {
            $cell->setValue($closure($cell));
        }
    }

    abstract public function cells(array $groups = array());

    abstract public function toArray(array $groups = array());
}
