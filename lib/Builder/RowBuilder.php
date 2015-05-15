<?php

namespace DTL\DataTable\Builder;

use DTL\DataTable\Cell;
use DTL\DataTable\Row;

class RowBuilder
{
    private $tableBuilder;
    private $cells = array();

    public function __construct(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }

    public function cell($value, array $groups = array())
    {
        $this->cells[] = new Cell($value, $groups);
        return $this;
    }

    public function getRow()
    {
        return new Row($this->cells);
    }

    public function end()
    {
        return $this->tableBuilder;
    }
}
