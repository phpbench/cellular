<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param array $groups
     */
    public function __construct(TableBuilder $tableBuilder = null, array $cells = array(), array $groups = array())
    {
        $this->tableBuilder = $tableBuilder;
        foreach ($cells as $column => $cell) {
            $this->cells[$column] = clone $cell;
        }
        $this->groups = $groups;
    }

    /**
     * Create a new instance.
     *
     * @param TableBuilder $tableBuilder
     * @param array $cells
     * @param array $groups
     */
    public static function create(TableBuilder $tableBuilder = null, array $cells = array(), array $groups = array())
    {
        return new self($tableBuilder, $cells, $groups);
    }

    /**
     * Create a new cell and place it at the specified column.
     *
     * @param mixed $column
     * @param mixed $value
     * @param array $groups
     */
    public function set($column, $value, array $groups = array())
    {
        $this->cells[$column] = new Cell($value, $groups);

        return $this;
    }

    /**
     * Return the named cell
     *
     * @param mixed $column
     * @return Cell
     */
    public function get($column)
    {
        return $this->cells[$column];
    }

    /**
     * Remove the cell in the named column
     *
     * @param string $column
     */
    public function remove($column)
    {
        unset($this->cells[$column]);
    }

    /**
     * Return the column names.
     *
     * @return array
     */
    public function getColumnNames()
    {
        return array_keys($this->cells);
    }

    /**
     * Create a new Row.
     *
     * @return Row
     */
    public function getRow()
    {
        return new Row($this->cells, $this->groups);
    }

    /**
     * Return the parent TableBuilder.
     */
    public function end()
    {
        if (null === $this->tableBuilder) {
            throw new \BadMethodCallException(sprintf(
                'This row builder with columns "%s" is not attached to a table builder, cannot call end()',
                implode('", "', array_keys($this->cells))
            ));
        }

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
