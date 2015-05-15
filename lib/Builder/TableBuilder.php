<?php

namespace DTL\DataTable\Builder;

use DTL\DataTable\Table;

class TableBuilder
{
    /**
     * Create a new table builder
     *
     * @return TableBuilder
     */
    public static function create()
    {
        return new $this;
    }

    /**
     * Create a new RowBuilder and return it
     *
     * @return RowBuilder
     */
    public function row()
    {
        $builder = new RowBuilder($this);
        $this->rows[] = $builder;

        return $builder;
    }

    /**
     * Create a new Table
     *
     * @return Table
     */
    public function getTable()
    {
        $rows = array();
        foreach ($this->rows as $rowBuilder) {
            $rows[] = $rowBuilder->getRow();
        }

        return new Table($rows);
    }
}
