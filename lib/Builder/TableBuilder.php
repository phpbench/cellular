<?php

namespace DTL\DataTable\Builder;

use DTL\DataTable\Table;

class TableBuilder
{
    /**
     * @var RowBuilder[]
     */
    private $rows = array();

    public function __construct(Table $table = null, array $groups = array())
    {
        if (null === $table) {
            return;
        }

        foreach ($table->getRows() as $row) {
            $cells = $row->getCells($groups);

            if (count($cells) === 0) {
                continue;
            }

            $this->rows[] = new RowBuilder($this, $row->getCells(), $row->getGroups());
        }
    }

    /**
     * Create a new table builder.
     *
     * An array of Row instances can be passed, these will be converted into
     * RowBuilder instances and the cells will be cloned.
     *
     * @param array $rows
     * @return TableBuilder
     */
    public static function create(Table $table = null, array $groups = array())
    {
        return new self($table, $groups);
    }

    /**
     * Create a new RowBuilder and return it
     *
     * @return RowBuilder
     */
    public function row(array $groups = array())
    {
        $builder = new RowBuilder($this, array(), $groups);
        $this->rows[] = $builder;

        return $builder;
    }

    /**
     * Return all of the row builders
     *
     * @return RowBuilder[]
     */
    public function getRowBuilders()
    {
        return $this->rows;
    }

    /**
     * Create a new Table
     *
     * @return Table
     */
    public function getTable()
    {
        $this->finalize();

        $rows = array();
        foreach ($this->rows as $row) {
            $rows[] = $row->getRow();
        }

        return new Table($rows);
    }

    /**
     * Fill in any empty cells with NULL
     */
    protected function finalize()
    {
        $columnNames = array();
        foreach ($this->rows as $rowBuilder) {
            foreach ($rowBuilder->getColumnNames() as $columnName) {
                $columnNames[$columnName] = $columnName;
            }
        }

        foreach ($this->rows as $rowBuilder) {
            $rowBuilder->order($columnNames);
        }
    }
}
