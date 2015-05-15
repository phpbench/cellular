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
    protected $cells;

    /**
     * @param array $cells
     */
    public function __construct(array $cells = array())
    {
        $this->cells = $cells;
    }

    /**
     * Return all the cells of this aggregateable instance.
     *
     * @param array $groups
     *
     * @return AggregateableInterface[]
     */
    public function getCells(array $groups = array())
    {
        if (empty($groups)) {
            return $this->cells;
        }

        return array_filter($this->cells, function (Cell $cell) use ($groups) {
            if ($cell->inGroups($groups)) {
                return true;
            }

            return false;
        });
    }

    /**
     * Return the cell with the given index
     *
     * @param int $index
     * @throws \OutOfBoundsException
     * @return Cell
     */
    public function getCell($index)
    {
        if (!array_key_exists($index, $this->cells)) {
            throw new \OutOfBoundsException(sprintf(
                'No cell exists at index "%d", index must be >= 0 and < %d',
                $index, count($this->cells)
            ));
        }

        return $this->cells[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function values(array $groups = array())
    {
        $values = array();
        foreach ($this->getCells($groups) as $cells) {
            foreach ($cells->values($groups) as $value) {
                $values[] = $value;
            }
        }

        return $values;
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
        foreach ($this->values($groups) as $value) {
            $result[] = $value;
        }

        return $result;
    }
}
