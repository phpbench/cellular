Data Table
==========

[![Build Status](https://travis-ci.org/dantleech/data-table.svg?branch=master)](https://travis-ci.org/dantleech/data-table) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dantleech/data-table/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dantleech/data-table/?branch=master)

The data table library provides an object oriented way of building, representing and analyzing tabular data.

Features:

- Supports aggregate functions `sum`, `avg`, `min`, `max` and `median`.
- Aggreate functions can applied to `Table`, `Row` and `Column`.
- Supports cell groups.
- Callbacks can be applied to cells on whole, or selected groups of `Table`,
  `Row` and `Column` instances.
- Produce grouped tables with callbacks - analagous to `SELECT bar, SUM(foo) FROM sometable GROUP BY bar`
- Fluent table builder

Note that this library is under development.

Creating
--------

Col 1 | Col 2 | Col 3
----- | ----- | -----
12    | 14    | 4
12    | 14    | 4

Would be created as follows:

````php
$table = Table::create();
$table->createAndAddRow()
    ->set('col1', 12)
    ->set('col2', 14)
    ->set('col3', 4);
$table->createAndAddRow()
    ->set('col1', 12)
    ->set('col2', 14)
    ->set('col3', 4)
````

Or without the builder:

````php
$table = new Table(
     new Row(array(
         'col1' => new Cell(12),
         'col2' => new Cell(14),
         'col3' => new Cell(4),
     )),
     new Row(array(
         'col1' => new Cell(12),
         'col2' => new Cell(14),
         'col3' => new Cell(4),
     )),
 );
````


Retrieving cell values
----------------------


````php
echo $table->sum(); // sum of table
echo $table->avg(); // average value of table

foreach ($table as $row) {
    echo $row->sum(); // average value of the row
    echo $row->avg(); // min value of row
}

echo $table->getColumn(0)->sum(); // sum of column
````

Assigning groups and accessing group data
-----------------------------------------

Groups can be used to analyze only certain cells:

````php
 $table = new Table(
     new Row(array(
         'col1' => new Cell(12, ['group1']),
         'col2' => new Cell(14, ['group1']),
         'col3' => new Cell(4, ['group2']),
     )),
 );

 echo $table->sum(['group1']); // 26
 echo $table->sum(['group2']); // 4
````

Applying a callback to each cell
--------------------------------

You can apply a callback to each cell on either a `Table` or a `Row`:

````php
$table = TableBuilder::create()
    ->row()
        ->set('col1', 'foobar')
    ->end()
    ->getTable();

$table->map(function (Cell $cell) {
    $cell->setValue($cell->getValue() + 1);
});
````

Other methods
-------------

- `fill`: Fill all matching cells with the given value

Aggregating/grouping table data
-------------------------------

You can aggregate the values in a table based on one or more unique cell
values in a given column.

````php
$table = new Table(
    new Row(array(
        'category' => new Cell('beer'),
        'quantity' => new Cell(14),
        'quality'  => new Cell(4),
    )),
    new Row(array(
        'category' => new Cell('beer'),
        'quantity' => new Cell(14),
        'quality'  => new Cell(4),
    )),
    new Row(array(
        'category' => new Cell('snitzel'),
        'quantity' => new Cell(14),
        'quality'  => new Cell(4),
    )),
);

$newInstance = $table->aggregate(function (Table $rowSet, RowBuilder $rowBuilder) {
    $rowBuilder->set('quantity', $rowSet->sum());
}, ['category']);

$newInstance->getRow(0)->getCell('quantity'); // 28 -- the values have been aggregated
````

Building upon existing tables
-----------------------------

Often you will need to add extra columns or rows to existing tables, for
example to add a column total. This can be done in two steps:

````php
$table = TableBuilder::create()
    ->row()
        ->set('price', 10)
    ->end()
    ->row()
        ->set('price', 20)
    ->end()
    ->getTable();

// get a new builder instance based on the existing table
$builder = $table->builder();

// add a new row with the total price
$builder
    ->row()
        ->set('price', $table->getColumn('price')->sum())
    ->end();

$table = $builder->getTable();

$table->toArray(); 

$expected = array(
    array(
        'price' => 10,
    ),
    array(
        'price' => 20,
    ),
    array(
        'price' => 30
    ),
);

var_dump($expected === $table->toArray()); // true
````

