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

    abstract public function values(array $groups = array());

    abstract public function toArray(array $groups = array());
}
