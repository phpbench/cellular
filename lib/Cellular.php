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
 * Abstract class for Table and Row and Column instances.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
abstract class Cellular extends Collection implements CellularInterface
{
    /**
     * @var array
     */
    protected $groups = array();

    /**
     * Return the values of all the cells contained in this cellular instance.
     *
     * @param array $groups
     *
     * @return array
     */
    public function getValues(array $groups = array())
    {
        $values = array();
        foreach ($this->getCells($groups) as $column => $cell) {
            $values[$column] = $cell->getValue();
        }

        return $values;
    }

    /**
     * Return all of the groups to which this cellular instance belongs.
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set the groups to which this cellular instance belongs.
     *
     * @param array $groups
     *
     * @return this
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Use a closure to assign a value to each cell in this cellular instance.
     *
     * @param \Closure
     * @param array $groups
     *
     * @return this
     */
    public function mapValues(\Closure $closure, array $groups = array())
    {
        foreach ($this->getCells($groups) as $cell) {
            $value = $closure($cell);
            $cell->setValue($value);
        }

        return $this;
    }

    abstract public function getCells(array $groups = array());
}
