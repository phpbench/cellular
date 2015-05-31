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
 * Abstract class for Table and Row and Column instances.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
abstract class Cellular extends Collection implements CellularInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function mapValues(\Closure $closure, array $groups = array())
    {
        foreach ($this->getCells($groups) as $cell) {
            $value = $closure($cell);
            $cell->setValue($value);
        }
    }

    abstract public function getCells(array $groups = array());
}
