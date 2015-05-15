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
     * @var Cell[]y
     */
    private $cells = array();

    /**
     * @param TableBuilder $tableBuilder
     */
    public function __construct(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }

    /**
     * Create a new cell and return $this
     *
     * @param mixed $value
     * @param array $groups
     */
    public function cell($value, array $groups = array())
    {
        $this->cells[] = new Cell($value, $groups);
        return $this;
    }

    /**
     * Create a new Row
     *
     * @return Row
     */
    public function getRow()
    {
        return new Row($this->cells);
    }

    /**
     * Return the parent TableBuilder
     */
    public function end()
    {
        return $this->tableBuilder;
    }
}
