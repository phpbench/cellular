<?php

namespace DTL\DataTable\Builder;

use DTL\DataTable\Table;

class TableBuilder
{
    public static function create()
    {
        return new $this;
    }

    public function row()
    {
        $builder = new RowBuilder($this);
        $this->rows[] = $builder;

        return $builder;
    }

    public function getTable()
    {
        $rows = array();
        foreach ($this->rows as $rowBuilder) {
            $rows[] = $rowBuilder->getRow();
        }

        return new Table($rows);
    }
}
