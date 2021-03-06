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
 * Represents a table.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Table extends Cellular
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * {@inheritDoc}
     */
    protected function validateElement($element)
    {
        if (!$element instanceof Row) {
            throw new InvalidCollectionTypeException($this, $element);
        }
    }

    /**
     * Return the table's title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the table's title.
     *
     * @param string $title
     *
     * @return Table
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Return the table's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the table's description.
     *
     * @param string $title
     *
     * @return Table
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Return all the rows.
     *
     * @return Row[]
     */
    public function getRows(array $groups = array())
    {
        if (empty($groups)) {
            return $this->getElements();
        }

        $rows = array();

        foreach ($this as $row) {
            foreach ($groups as $group) {
                if (in_array($group, $row->getGroups())) {
                    $rows[] = $row;
                }
            }
        }

        return $rows;
    }

    /**
     * Add a row to the table.
     *
     * @param Row $row
     */
    public function addRow(Row $row)
    {
        $this[] = $row;
    }

    /**
     * Reurn a new row with the given groups.
     *
     * @param string[] $groups
     *
     * @return Row
     */
    public function createRow(array $groups = array())
    {
        $row = new Row(array());
        $row->setGroups($groups);

        return $row;
    }

    /**
     * Create a new row, add it to this table then return it.
     *
     * @param string[] $groups
     *
     * @return Row
     */
    public function createAndAddRow(array $groups = array())
    {
        $row = $this->createRow($groups);
        $this->addRow($row);

        return $row;
    }

    /**
     * Return the column with the given name.
     *
     * @return string Column
     */
    public function getColumn($name)
    {
        return new Column($this, $name);
    }

    /**
     * Return all the column names.
     *
     * @return Column[]
     */
    public function getColumnNames(array $groups = array())
    {
        $columnNames = array();

        foreach ($this->getElements() as $row) {
            foreach ($row->getColumnNames($groups) as $columnName) {
                $columnNames[$columnName] = $columnName;
            }
        }

        return array_values($columnNames);
    }

    /**
     * Return all the columns.
     *
     * @return Column[]
     */
    public function getColumns(array $groups = array())
    {
        $columns = array();
        foreach ($this->getColumnNames($groups) as $columnName) {
            $columns[] = $this->getColumn($columnName);
        }

        return $columns;
    }

    /**
     * Return the number of columns.
     *
     * Note if the number of cells is not uniform, the number of columns
     * will reflect the row with the least number of cells.
     *
     * @return int
     */
    public function getColumnCount(array $groups = array())
    {
        $min = null;
        foreach ($this->getElements() as $row) {
            $cellCount = count($row->getCells($groups));
            if ($min === null || $cellCount < $min) {
                $min = $cellCount;
            }
        }

        return $min;
    }

    /**
     * Return the row with the given index.
     *
     * @param int $index
     *
     * @throws \OutOfRangeException
     *
     * @return Row
     */
    public function getRow($index)
    {
        if (!isset($this[$index])) {
            throw new \OutOfRangeException(sprintf(
                'Row with index "%s" does not exist. Must be >=0 < %d',
                $index, count($this)
            ));
        }

        return $this[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(array $groups = array())
    {
        $cells = array();
        foreach ($this as $row) {
            foreach ($row->getCells($groups) as $cell) {
                $cells[] = $cell;
            }
        }

        return $cells;
    }

    /**
     * Return an array representation of this table.
     *
     * @param array $groups
     *
     * @return array
     */
    public function toArray(array $groups = array())
    {
        $result = array();
        foreach ($this as $row) {
            $result[] = $row->toArray($groups);
        }

        return $result;
    }

    /**
     * Align the table.
     *
     * This happens in two passes. The first determines all
     * of the existing column names. The second orders all
     * rows based on the determined column names.
     *
     * Rows will be filled with NULL valued cells where columns
     * are missing.
     */
    public function align()
    {
        $columnNames = array();
        foreach ($this as $row) {
            $columnNames = array_merge(
                $columnNames,
                $row->getColumnNames()
            );
        }

        foreach ($this as $row) {
            $row->order($columnNames);
        }
    }
}
