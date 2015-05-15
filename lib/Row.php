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

    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
    }

    public function createCell($value, array $groups = array())
    {
        return new Cell($value, $groups);
    }

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
}
