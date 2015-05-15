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
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Return the column with the given index
     *
     * @return Column
     */
    public function getColumn($index)
    {
        return new Column($this, $index);
    }

    /**
     * Return all the columns
     *
     * @return Column[]
     */
    public function getColumns()
    {
        $columns = array();

        for ($index = 0; $index < $this->getColumnCount(); $index++) {
            $columns[] = $this->getColumn($index);
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
    public function getColumnCount()
    {
        $min = null;
        foreach ($this->rows as $row) {
            $cellCount = count($row->getCells());
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
    public function values(array $groups = array())
    {
        $values = array();
        foreach ($this->rows as $row) {
            foreach ($row->values($groups) as $value) {
                $values[] = $value;
            }
        }

        return $values;
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

            return array(new Row($cells));
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
            $aggregateRows = $table->aggregate();
            $rows[] = reset($aggregateRows);
        }

        return new Table($rows);
    }
}
