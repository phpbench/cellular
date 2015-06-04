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

use DTL\Cellular\Exception\InvalidCollectionTypeException;

class Workspace extends Cellular
{
    /**
     * {@inheritDoc}
     */
    protected function validateElement($element)
    {
        if (!$element instanceof Table) {
            throw new InvalidCollectionTypeException($this, $element);
        }
    }

    /**
     * Return all the tables.
     *
     * @return Table[]
     */
    public function getTables(array $groups = array())
    {
        if (empty($groups)) {
            return $this->getElements();
        }

        $tables = array();

        foreach ($this as $table) {
            foreach ($groups as $group) {
                if (in_array($group, $table->getGroups())) {
                    $tables[] = $table;
                }
            }
        }

        return $tables;
    }

    /**
     * Add a table to the workspace.
     *
     * @param Table $table
     */
    public function addTable(Table $table)
    {
        $this[] = $table;
    }

    /**
     * Reurn a new table with the given groups.
     *
     * @param string[] $groups
     *
     * @return Table
     */
    public function createTable(array $groups = array())
    {
        $table = new Table(array());
        $table->setGroups($groups);

        return $table;
    }

    /**
     * Create a new table, add it to this table then return it.
     *
     * @param string[] $groups
     *
     * @return Table
     */
    public function createAndAddTable(array $groups = array())
    {
        $table = $this->createTable($groups);
        $this->addTable($table);

        return $table;
    }

    /**
     * Return the table with the given index.
     *
     * @param int $index
     *
     * @thtables \OutOfRangeException
     *
     * @return Table
     */
    public function getTable($index)
    {
        if (!isset($this[$index])) {
            throw new \OutOfRangeException(sprintf(
                'Table with index "%s" does not exist. Must be >=0 < %d',
                $index, count($this)
            ));
        }

        return $this[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(array $groups = array())
    {
        $cells = array();
        foreach ($this as $table) {
            foreach ($table->getCells($groups) as $cell) {
                $cells[] = $cell;
            }
        }

        return $cells;
    }

    /**
     * Return an array representation of this table.
     *
     * @param array $groups
     *
     * @return array
     */
    public function toArray(array $groups = array())
    {
        $result = array();
        foreach ($this as $table) {
            $result[] = $table->toArray($groups);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return array();
    }
}
