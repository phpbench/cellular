<?php

namespace DTL\DataTable\Builder;

use DTL\DataTable\Cell;
use DTL\DataTable\Row;

class RowBuilder
{
    /**
     * @var TableBuilder
     */
    private $tableBuilder;

    /**
     * @var Cell[]
     */
    private $cells = array();

    /**
     * @var string[]
     */
    private $groups = array();

    /**
     * @param TableBuilder $tableBuilder
     * @param array $cells
     */
    public function __construct(TableBuilder $tableBuilder, array $cells = array(), array $groups = array())
    {
        $this->tableBuilder = $tableBuilder;
        foreach ($cells as $column => $cell) {
            $this->cells[$column] = clone $cell;
        }
        $this->groups = $groups;
    }

    /**
     * Create a new cell and place it at the specified column
     *
     * @param mixed $value
     * @param array $groups
     */
    public function set($column, $value, array $groups = array())
    {
        $this->cells[$column] = new Cell($value, $groups);
        return $this;
    }

    /**
     * Return true if the row builder as the given column name set
     *
     * @return bool
     */
    public function has($column)
    {
        return array_key_exists($column, $this->cells);

    }

    /**
     * Return the column names
     *
     * @return array
     */
    public function getColumnNames()
    {
        return array_keys($this->cells);
    }

    /**
     * Create a new Row
     *
     * @return Row
     */
    public function getRow()
    {
        return new Row($this->cells, $this->groups);
    }

    /**
     * Return the parent TableBuilder
     */
    public function end()
    {
        return $this->tableBuilder;
    }

    /**
     * Set the column order according to the positions of the given column names.
     * Non-existing columns will be created with NULL values.
     *
     * @param array $columnOrder
     */
    public function order($columnOrder)
    {
        $newOrder = array();
        foreach ($columnOrder as $columnName) {
            $newOrder[$columnName] = isset($this->cells[$columnName]) ? $this->cells[$columnName] : new Cell(null);
        }

        $this->cells = $newOrder;
    }
}
