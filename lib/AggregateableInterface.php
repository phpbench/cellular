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

/**
 * AggregateableInterface instances can return aggregate information about their elements.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface AggregateableInterface
{
    /**
     * Return the sum of all groupged cells.
     *
     * @param array $groups
     *
     * @return mixed
     */
    public function sum(array $groups = array());

    /**
     * Return the minimum value amongst all groupged cells.
     *
     * @param array $groups
     *
     * @return mixed
     */
    public function min(array $groups = array());

    /**
     * Return the maximum value amongst all groupged cells.
     *
     * @param array $groups
     *
     * @return mixed
     */
    public function max(array $groups = array());

    /**
     * Return the average value of all groupged cells.
     *
     * @param array $groups
     *
     * @return mixed
     */
    public function avg(array $groups = array());

    /**
     * Return the median value of all groupged cells.
     *
     * @param array $groups
     * @param mixed $ceil
     *
     * @return mixed
     */
    public function median(array $groups = array(), $ceil = false);

    /**
     * Return the values of all groupged cells.
     *
     * @param array $groups
     *
     * @return array
     */
    public function values(array $groups = array());

    /**
     * Return the group names of which this instance is a part.
     *
     * @return array
     */
    public function getGroups();

    /**
     * Fill the table with a value.
     *
     * @param mixed $value
     * @param array $groups
     */
    public function fill($value, array $groups = array());

    /**
     * Apply a closure to each cell.
     *
     * @param \Closure $closure
     * @param array $groups
     */
    public function map(\Closure $closure, array $groups = array());
}
