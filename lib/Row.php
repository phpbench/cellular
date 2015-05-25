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

use DTL\DataTable\Cell;

/**
 * Represents a table row.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Row extends Aggregated
{
    /**
     * @var Row[]
     */
    private $cells;

    /**
     * @var string[]
     */
    private $groups;

    /**
     * @param array $cells
     */
    public function __construct(array $cells = array(), array $groups = array())
    {
        $this->cells = $cells;
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
     * Return all column names.
     *
     * @param array $groups
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
     * @throws \OutOfBoundsException
     * @return Cell
     */
    public function getCell($column)
    {
        if (!array_key_exists($column, $this->cells)) {
            throw new \OutOfBoundsException(sprintf(
                'No cell exists at column "%s", known columns: "%s"',
                $column, implode('", "', array_keys($this->cells))
            ));
        }

        return $this->cells[$column];
    }

    /**
     * Set or create a cell by values
     *
     * @param string $columnName
     * @param mixed $value
     * @param array $groups
     */
    public function set($columnName, $value, array $groups = array())
    {
        if (!isset($this->cells[$columnName])) {
            $this->cells[$columnName] = new Cell($value, $groups);
        } else {
            $this->cells[$columnName]->setValue($value);
        }

        return $this;
    }

    public function remove($columnName)
    {
        unset($this->cells[$columnName]);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(array $groups = array())
    {
        if (empty($groups)) {
            return $this->cells;
        }

        return array_filter($this->cells, function (Cell $cell) use ($groups) {
            foreach ($groups as $group) {
                if (in_array($group, $cell->getGroups())) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Set the cells
     *
     * @param Cell[]
     */
    public function setCells(array $cells)
    {
        $this->cells = $cells;
    }

    /**
     * Return an array representation of this row.
     *
     * @param array $groups
     * @return array
     */
    public function toArray(array $groups = array())
    {
        $result = array();
        foreach ($this->values($groups) as $column => $value) {
            $result[$column] = $value;
        }

        return $result;
    }
}
