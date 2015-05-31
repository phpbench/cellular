<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Cellular\Tests\Benchmark;

use PhpBench\Benchmark;
use DTL\Cellular\Cell;
use DTL\Cellular\Row;
use DTL\Cellular\Table;
use PhpBench\Benchmark\Iteration;

/**
 * @revs 10
 * @revs 100
 * @revs 1000
 * @revs 10000
 * @iterations 4
 * @processIsolation iteration
 */
class TableBench implements Benchmark
{
    /**
     * @description Create table with plain OOP
     */
    public function benchCreateTable()
    {
        new Table(array(
            new Row(array(
                'key' => new Cell('a'),
                'num' => new Cell(10),
                'rand' => new Cell(rand(0, 100000)),
            )),
            new Row(array(
                'key' => new Cell('a'),
                'num' => new Cell(10),
            )),
            new Row(array(
                'key' => new Cell('b'),
                'num' => new Cell(10),
            )),
        ));
    }

    /**
     * @description Create table with plain OOP and aggregate it
     */
    public function benchAggregatePlainPhp()
    {
        $table = new Table(array(
            new Row(array(
                'key' => new Cell('a'),
                'num' => new Cell(10),
                'rand' => new Cell(rand(0, 100000)),
            )),
            new Row(array(
                'key' => new Cell('a'),
                'num' => new Cell(10),
            )),
            new Row(array(
                'key' => new Cell('b'),
                'num' => new Cell(10),
            )),
        ));

        $table->partition(function ($row) {
            return $row['num'];
        })->fork(function ($table, $newTable) {
            $row = $newTable->createAndAddRow();
            $row->set('num', $table->getColumn('num')->sum());
        });
    }

    /**
     * @description Create table with the builder
     */
    public function benchCreateBuilder()
    {
        $table = Table::create();
        $table->createAndAddRow()
            ->set('key', 'a')
            ->set('num', 10);
        $table->createAndAddRow()
            ->set('key', 'a')
            ->set('num', 10);
        $table->createAndAddRow()
            ->set('key', 'b')
            ->set('num', 10);
    }

    /**
     * @description Create table and aggregate it
     */
    public function benchAggregate()
    {
        $table = Table::create();
        $table->createAndAddRow()
            ->set('key', 'a')
            ->set('num', 10);
        $table->createAndAddRow()
            ->set('key', 'a')
            ->set('num', 10);
        $table->createAndAddRow()
            ->set('key', 'b')
            ->set('num', 10);

        $table->partition(function ($row) {
            return $row['num'];
        })->fork(function ($table, $newTable) {
            $row = $newTable->createAndAddRow();
            $row->set('num', $table->getColumn('num')->sum());
        });
    }
}
