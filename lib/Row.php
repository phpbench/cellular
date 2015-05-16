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

class Row extends Aggregated
{
    /**
     * @var Row[]
     */
    private $cells;

    /**
     * @var string[]
     */
    private $groups;

    /**
     * @param array $cells
     */
    public function __construct(array $cells = array(), array $groups = array())
    {
        $this->cells = $cells;
        $this->groups = $groups;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Return all column names
     *
     * @return array
     */
    public function getColumnNames(array $groups = array())
    {
        return array_keys($this->cells($groups));
    }

    /**
     * Return the cell at the given column
     *
     * @param int $column
     * @throws \OutOfBoundsException
     * @return Cell
     */
    public function getCell($column)
    {
        if (!array_key_exists($column, $this->cells)) {
            throw new \OutOfBoundsException(sprintf(
                'No cell exists at column "%d", known columns: "%s"',
                $column, implode('", "', array_keys($this->cells))
            ));
        }

        return $this->cells[$column];
    }

    /**
     * {@inheritDoc}
     */
    public function cells(array $groups = array())
    {
        if (empty($groups)) {
            return $this->cells;
        }

        return array_filter($this->cells, function (Cell $cell) use ($groups) {
            foreach ($groups as $group) {
                if (in_array($group, $cell->getGroups())) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Fill the row with a value
     *
     * @param mixed $value
     * @param array $groups
     */
    public function fill($value, array $groups = array())
    {
        foreach ($this->cells($groups) as $cell) {
            $cell->setValue($value);
        }
    }

    /**
     * Return an array representation of this row
     *
     * @param array $groups
     * @return array
     */
    public function toArray(array $groups = array())
    {
        $result = array();
        foreach ($this->values($groups) as $column => $value) {
            $result[$column] = $value;
        }

        return $result;
    }
}
