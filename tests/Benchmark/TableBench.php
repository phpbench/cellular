<?php

namespace DTL\DataTable\Tests\Benchmark;

use PhpBench\Benchmark;
use DTL\DataTable\Builder\TableBuilder;
use DTL\DataTable\Cell;
use DTL\DataTable\Row;
use DTL\DataTable\Table;
use PhpBench\Benchmark\Iteration;

class TableBench implements Benchmark
{
    /**
     * @description Create table with plain OOP
     * @beforeMethod benchCreateTable
     * @iterations 1000
     */
    public function benchCreateTable()
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
        unset($table);
    }

    /**
     * @description Create table with the builder
     * @beforeMethod benchCreateBuilder
     * @iterations 1000
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
     * @iterations 1000
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
