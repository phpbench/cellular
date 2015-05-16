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

/**
 * Represents a table column
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Column extends Aggregated
{
    /**
     * @var Table
     */
    private $table;

    /**
     * @var int
     */
    private $key;

    /**
     * @param Table $table
     * @param mixed $key
     */
    public function __construct(Table $table, $key)
    {
        $this->table = $table;
        $this->key = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(array $groups = array())
    {
        $cells = array();
        foreach ($this->table->getRows() as $row) {
            $cell = $row->getCell($this->key);
            if (!empty($groups) && !$cell->inGroup($groups)) {
                continue;
            }

            $cells[] = $cell;
        }

        return $cells;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        $groups = array();
        foreach ($this->getCells() as $cell) {
            foreach ($cell->getGroups() as $group) {
                $groups[$group] = $group;
            }
        }

        return array_values($groups);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(array $groups = array())
    {
        return $this->values($groups);
    }
}
