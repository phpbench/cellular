<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Cellular;

/**
 * Represents a table column.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Column implements CellularInterface
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
            if (!isset($row[$this->key])) {
                continue;
            }
            $cell = $row[$this->key];
            foreach ($groups as $group) {
                if (!in_array($group, $cell->getGroups())) {
                    continue 2;
                }
            }

            $cells[] = $cell;
        }

        return $cells;
    }

    /**
     * {@inheritDoc}
     */
    public function getValues(array $groups = array())
    {
        $values = array();
        foreach ($this->getCells($groups) as $column => $cell) {
            $values[$column] = $cell->getValue();
        }

        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function mapValues(\Closure $closure, array $groups = array())
    {
        foreach ($this->getCells($groups) as $cell) {
            $value = $closure($cell);
            $cell->setValue($value);
        }
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
        return $this->getValues($groups);
    }
}
