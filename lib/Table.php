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

use DTL\DataTable\Column;

class Table extends Aggregated
{
    private $rows;

    public function __construct(array $rows = array())
    {
        $this->rows = $rows;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function createRow()
    {
        return new Row();
    }

    public function getColumn($index)
    {
        return new Column($this, $index);
    }

    public function values(array $groups = array())
    {
        $values = array();
        foreach ($this->rows as $row) {
            foreach ($row->values($groups) as $value) {
                $values[] = $value;
            }
        }

        return $values;
    }
}
