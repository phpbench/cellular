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

use DTL\DataTable\Builder\TableBuilder;
use DTL\DataTable\Builder\RowBuilder;
use DTL\DataTable\Table;
use DTL\DataTable\Row;

/**
 * Represents a table.
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

    public static function create()
    {
        return new self();
    }

    /**
     * Return all the rows.
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
     * Add a row to the table.
     *
     * @param Row $row
     */
    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }


    public function createRow(array $cells = array(), array $groups = array())
    {
        return new Row($cells, $groups);
    }

    public function createAndAddRow(array $cells = array(), array $groups = array())
    {
        $row = $this->createRow($cells, $groups);
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

        foreach ($this->rows as $row) {
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
        foreach ($this->rows as $row) {
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
     * Return an array representation of this table.
     *
     * @param array $groups
     *
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
     * Return a new table instance with only the rows which
     * contain unique column names according to $columnNames.
     *
     * The callback accepts, for each set of unique $columnNames, a
     * Table with the unique set and the Row instance which will represent
     * that set in the final Table instance.
     *
     * For example:
     *
     * ````
     * $aggregatedTable = $table->aggregate(
     *     function (Table $rowSet, Row $newRow) {
     *         $newRow->set('foo', $rowSet->getColumn('foo')->sum());
     *     }.
     *     array('col1', 'col2'),
     * );
     * ````
     *
     * @param array $columnNames
     * @param array $groups
     * @return Table $callback
     */
    public function aggregate(\Closure $callback, array $columnNames = array(), array $groups = array())
    {
        $rowSets = array();
        $aggregatedTable = self::create();

        $rows = $this->getRows($groups);
        foreach ($rows as $row) {
            $key = '';
            foreach ($columnNames as $columnName) {
                $key .= $row->getCell($columnName)->getValue();
            }

            if (!isset($rowSets[$key])) {
                $rowSets[$key] = Table::create();
            }

            $rowSets[$key]->createAndAddRow($row->getCells($groups), $row->getGroups());
        }

        foreach ($rowSets as $rowSet) {
            $rows = $rowSet->getRows();
            $firstRow = reset($rows);
            $callback($rowSet, $firstRow);
            $aggregatedTable->addRow($firstRow);
        }

        return $aggregatedTable;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return array();
    }
}
