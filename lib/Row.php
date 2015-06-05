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

use DTL\Cellular\Exception\InvalidCollectionTypeException;

/**
 * Represents a table row.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Row extends Cellular
{
    /**
     * {@inheritDoc}
     */
    protected function validateElement($element)
    {
        if (!$element instanceof Cell) {
            throw new InvalidCollectionTypeException($this, $element);
        }
    }

    /**
     * Return all column names.
     *
     * @param array $groups
     *
     * @return array
     */
    public function getColumnNames(array $groups = array())
    {
        return array_keys($this->getCells($groups));
    }

    /**
     * Return the cell at the given column.
     *
     * @param int $column
     *
     * @throws \OutOfBoundsException
     *
     * @return Cell
     */
    public function getCell($column)
    {
        return $this[$column];
    }

    /**
     * Modify or create a cell.
     *
     * @param string $columnName
     * @param Cell $cell
     */
    public function setCell($columnName, Cell $cell)
    {
        $primary = $this->getPrimaryPartition();
        $primary->set($columnName, $cell);
    }

    /**
     * Modify or create a cell.
     *
     * @param string $columnName
     * @param mixed $value
     * @param array $groups
     *
     * @return Row
     */
    public function set($columnName, $value, array $groups = array())
    {
        $primary = $this->getPrimaryPartition();

        if ($primary->exists($columnName)) {
            $primary->get($columnName)->setValue($value);
        } else {
            $this->setCell($columnName, new Cell($value, $groups));
        }

        return $this;
    }

    public function remove($columnName)
    {
        unset($this[$columnName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(array $groups = array())
    {
        if (empty($groups)) {
            return $this->getElements();
        }

        return $this->filter(function (Cell $cell) use ($groups) {
            foreach ($groups as $group) {
                if (in_array($group, $cell->getGroups())) {
                    return true;
                }
            }

            return false;
        })->getElements();
    }

    /**
     * Return an array representation of this row.
     *
     * @param array $groups
     *
     * @return array
     */
    public function toArray(array $groups = array())
    {
        $result = array();
        foreach ($this->getValues($groups) as $column => $value) {
            $result[$column] = $value;
        }

        return $result;
    }

    /**
     * Order the cells.
     */
    public function order(array $columnNames = array())
    {
        $this->assertSinglePartition(__METHOD__);

        $partition = $this->getPrimaryPartition();
        $this->clear();

        foreach ($columnNames as $columnName) {
            if (!$partition->exists($columnName)) {
                $this->setCell($columnName, new Cell(null));
                continue;
            }

            $this->setCell($columnName, $partition->get($columnName));
        }
    }
}
