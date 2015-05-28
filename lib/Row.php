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
     * @var string[]
     */
    private $groups;

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
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
        if (!array_key_exists($column, $this->getElements())) {
            throw new \OutOfBoundsException(sprintf(
                'No cell exists at column "%s", known columns: "%s"',
                $column, implode('", "', array_keys($this->getElements()))
            ));
        }

        return $this->getElements()[$column];
    }

    /**
     * Set or create a cell
     *
     * @param string $columnName
     * @param mixed $value
     * @param array $groups
     * @return this
     */
    public function setCell($columnName, $value, array $groups = array())
    {
        if ($this->getPrimaryPartition()->exists($columnName)) {
            $this->getPrimaryPartition()->get($columnName)->setValue($value);
        } else {
            $this->getPrimaryPartition()->set($columnName, new Cell($value, $groups));
        }

        return $this;
    }

    /**
     * Synonym for setCell
     *
     * @param string $columnName
     * @param mixed $value
     * @param array $groups
     * @return this
     */
    public function set($columnName, $value, array $groups = array())
    {
        return $this->setCell($columnName, $value, $groups);
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

    public function order(array $columnNames = array())
    {
        $this->assertSinglePartition(__METHOD__);

        $newOrder = array();

        foreach ($columnNames as $columnName) {
            $newOrder[$columnName] = isset($this[$columnName]) ? $this[$columnName] : new Cell(null);
        }

        $this->replacePartition(new Partition($newOrder));
    }
}
