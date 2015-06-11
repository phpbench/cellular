Cellular
========

[![Build Status](https://travis-ci.org/dantleech/cellular.svg?branch=master)](https://travis-ci.org/dantleech/cellular) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dantleech/cellular/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dantleech/cellular/?branch=master)

The cellular library provides an object oriented way of building, representing and analyzing tabular data.

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

You can retrieve values from `Table`, `Row` and `Column` instances as follows:

````php
$table->getValues(); // return an array containg all cell values
$table->getRow(0)->getValues();
$table->getColumn('col1')->getValues();
````

Groups
------

You apply groups to cells:

````php
$table->createAndAddRow()
    ->set('hello, 'vaue', array('group1'));

var_dump($table->getCells(array('group1'))); // dump all cells in group1

$table->mapValues(function ($value) {
    return 'goodbye';
}, array('group1')); // set the value of all cells in group1 to "goodbye"
````

Using the Calculator
--------------------

The calculator allows you to apply certain calculations to values, `Cell`
instances or any instance of `CellularInterface`:

````php
$mean = Calculator::mean($table); // return the mean (average) value of the table

$median = Calculator::median($table->getRow(0)); // return the median value of the first row

$deviation = Calculator::deviation(
    Calculator::mean($table->getColumn('col1')),
    $table->getRow(0)->getCell('col1')
); // return the deviation of "col1" in the first row as a percentage from the average value of "col1"
````

Current functions:

- `sum`: Return the sum of values
- `min`: Return the minimum value
- `max`: Return the maximum value
- `mean`: Return the mean (average) value
- `median`: Return the median value
- `deviation`: Return the deviation as a percentage

Partitioning and Forking
------------------------

`Table` and `Row` instances provide the following methods:

- `partition`: Internally divide the collection of elements according to a
  given callback.
- `aggregate`: Aggregate the partitions of a table back to a single partition.

This is useful for creating summaries from multiple tables or rows:

For example:

````php
$table
    ->partition(function ($row) {
        return $row['class'];
    })
    ->partition(function ($table, $newInstance) use ($cols, &$newCols, $options, $functions) {
        $newInstance->createAndAddRow()
            ->set(
                'number', 
                Calculator::sum($table->getColumn('number'))
            );
    });
````

The callback is passed each partition in turn alongisde a new instance
of the collection class. The callback's responsiblity is to add new rows to
the new instance, the primary partition of which will become the new primary
partition for the collection upon which the operation is performed.

The result will be anagous to the following SQL: `SELECT SUM(number) FROM table GROUP BY class`.

Sorting
-------

Sorting can be achieved as follows:

````php
$table->sort(function ($row1, $row2) {
    return $row1['col1'] > $row2['col1'];
});
````

Evaluating values
-----------------

Values can be evaluated as follows:

````php
$sum = $table->evaluate(function ($row, $lastValue) {
    $lastValue += $row['cell']->getValue();
}, 0); // evaluates the sum of the column "cell"
````

The callback is passed the element and the previous result. The initial result
is given as the second argument to `evaluate`.

Setting Attributes
------------------

It is possible to set attributes on `Cell` and `Cellular` instances. This is
useful when you need to store metadata about the source of the data before it
was transformed into cellular form.

````php
$table->setAttribute('foo', 'bar');
$table->getAttribute('foo');
$table->hasAttribute('foo');
````

Other methods
-------------

- `filter`: Filter the results by a closure. Returns a new instance.
- `clear`: Clear the collection
