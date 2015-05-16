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

use DTL\DataTable\Column;
use DTL\DataTable\Builder\TableBuilder;
use DTL\DataTable\Table;

/**
 * Represents a table
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Table extends Aggregated
{
    /**
     * @var Row[]
     */
    private $rows;

    /**
     * @param array $rows
     */
    public function __construct(array $rows = array())
    {
        $this->rows = $rows;
    }

    /**
     * Create a new table builder
     *
     * @return TableBuilder
     */
    public static function createBuilder()
    {
        return new TableBuilder();
    }

    /**
     * Return all the rows
     *
     * @return Row[]
     */
    public function getRows(array $groups = array())
    {
        if (empty($groups)) {
            return $this->rows;
        }

        $rows = array();

        foreach ($this->rows as $row) {
            foreach ($groups as $group) {
                if (in_array($group, $row->getGroups())) {
                    $rows[] = $row;
                }
            }
        }

        return $rows;
    }

    /**
     * Return the column with the given name
     *
     * @return Column
     */
    public function getColumn($name)
    {
        return new Column($this, $name);
    }

    /**
     * Return all the column names
     *
     * @return Column[]
     */
    public function getColumnNames(array $groups = array())
    {
        $columnNames = array();

        foreach ($this->rows as $row) {
            foreach ($row->getColumnNames($groups) as $columnName) {
                $columnNames[$columnName] = $columnName;
            }
        }

        return array_values($columnNames);
    }

    /**
     * Return all the columns
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
     * @return integer
     */
    public function getColumnCount(array $groups = array())
    {
        $min = null;
        foreach ($this->rows as $row) {
            $cellCount = count($row->getCells($groups));
            if ($min === null || $cellCount < $min) {
                $min = $cellCount;
            }
        }

        return $min;
    }

    /**
     * Return the row with the given index
     *
     * @param int $index
     * @throws \OutOfRangeException
     * @return Row
     */
    public function getRow($index)
    {
        if (!isset($this->rows[$index])) {
            throw new \OutOfRangeException(sprintf(
                'Row with index "%s" does not exist. Must be >=0 < %d',
                $index, count($this->rows)
            ));
        }

        return $this->rows[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(array $groups = array())
    {
        $cells = array();
        foreach ($this->rows as $row) {
            foreach ($row->getCells($groups) as $cell) {
                $cells[] = $cell;
            }
        }

        return $cells;
    }

    /**
     * Return an array representation of this table
     *
     * @param array $groups
     * @return array
     */
    public function toArray(array $groups = array())
    {
        $result = array();
        foreach ($this->rows as $row) {
            $result[] = $row->toArray($groups);
        }

        return $result;
    }

    /**
     * Return a new table that has rows representing the grouped and
     * aggregated values based on the given column indexes.
     *
     * TODO: This is wrong.
     *
     * @param array $columnIndexes
     * @return array
     */
    public function aggregate(array $columnIndexes = array())
    {
        $newRowSets = array();

        if (empty($columnIndexes)) {
            $cells = array();
            foreach ($this->getColumns() as $column) {
                $cells[] = new Cell($column->sum());
            }

            return new Table(array(new Row($cells)));
        } 
        foreach ($this->getRows() as $row) {
            $key = '';
            foreach ($columnIndexes as $columnIndex) {
                $key .= $row->getCell($columnIndex)->value();
            }

            if (!isset($newRowSets[$key])) {
                $newRowSets[$key] = array($row);
            } else {
                $newRowSets[$key][] = $row;
            }
        }

        $rows = array();
        foreach ($newRowSets as $newRowSet) {
            $table = new Table($newRowSet);
            $aggregateRows = $table->aggregate()->getRows();
            $rows[] = reset($aggregateRows);
        }

        return new Table($rows);
    }

    public function builder(array $groups = array())
    {
        return TableBuilder::create($this, $groups);
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return array();
    }
}
