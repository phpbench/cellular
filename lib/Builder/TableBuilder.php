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

use DTL\DataTable\Table;

class TableBuilder
{
    /**
     * @var RowBuilder[]
     */
    private $rows = array();

    /**
     * @param Row[] $rows
     */
    public function __construct(array $rows = array())
    {
        foreach ($rows as $row) {
            $this->rows[] = RowBuilder::create($this, $row->getCells(), $row->getGroups());
        }
    }

    /**
     * Create a new table builder.
     *
     * An array of Row instances can be passed, these will be converted into
     * RowBuilder instances and the cells will be cloned.
     *
     * @param array $rows
     *
     * @return TableBuilder
     */
    public static function create(array $rows = array(), array $groups = array())
    {
        return new self($rows, $groups);
    }

    /**
     * Create a new RowBuilder and return it.
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
     * Add a row builder, returns this instance.
     *
     * @return TableBuilder
     */
    public function addRow(RowBuilder $row)
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * Return all of the row builders.
     *
     * @return RowBuilder[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Create a new Table.
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
     * Fill in any empty cells with NULL.
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
