<?php

namespace DTL\DataTable\Tests\Benchmark;

use PhpBench\Benchmark;
use DTL\DataTable\Builder\TableBuilder;
use DTL\DataTable\Cell;
use DTL\DataTable\Row;
use DTL\DataTable\Table;
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

        $table->aggregate(function ($table, $row) {
            $row->set('num', $table->getColumn('num')->sum());
        });
    }

    /**
     * @description Create table with the builder
     */
    public function benchCreateBuilder()
    {
        TableBuilder::create()
            ->row()
                ->set('key', 'a')
                ->set('num', 10)
            ->end()
            ->row()
                ->set('key', 'a')
                ->set('num', 10)
            ->end()
            ->row()
                ->set('key', 'b')
                ->set('num', 10)
            ->end()
            ->getTable();
    }

    /**
     * @description Create table and aggregate it
     */
    public function benchAggregate()
    {
        $table = TableBuilder::create()
            ->row()
                ->set('key', 'a')
                ->set('num', 10)
            ->end()
            ->row()
                ->set('key', 'a')
                ->set('num', 10)
            ->end()
            ->row()
                ->set('key', 'b')
                ->set('num', 10)
            ->end()
            ->getTable();

        $table->aggregate(function ($table, $row) {
            $row->set('num', $table->getColumn('num')->sum());
        });
    }
}
