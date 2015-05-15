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

class Column extends Aggregated
{
    private $table;
    private $index;

    public function __construct(Table $table, $index)
    {
        $this->table = $table;
        $this->index = $index;
    }

    /**
     * {@inheritDoc}
     */
    public function values(array $groups = array())
    {
        $values = array();
        foreach ($this->table->getRows() as $row) {
            $cell = $row->getCell($this->index);
            if (!empty($groups) && !$cell->inGroup($groups)) {
                continue;
            }

            $values[] = $cell->value();
        }

        return $values;
    }
}
