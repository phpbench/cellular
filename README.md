Data Table
==========

[![Build Status](https://travis-ci.org/dantleech/data-table.svg?branch=master)](https://travis-ci.org/dantleech/data-table)

The data table library provides an object oriented way of representing and analyzing tabular data.

Features:

- Supports aggregate `sum`, `avg`, `min`, `max` and `median`.
- Aggreate on `Table`, `Row` or `Column`.
- Supports cell groups.

Creating
--------

Col 1 | Col 2 | Col 3
----- | ----- | -----
12    | 14    | 4
12    | 14    | 4

 Would be created as follows:

 ````php
 $table = new Table(
     new Row(array(
         new Cell(12),
         new Cell(14),
         new Cell(4),
     )),
     new Row(array(
         new Cell(12),
         new Cell(14),
         new Cell(4),
     )),
 );
````

Aggregating
-----------

All elements implement an aggregateable interface, allowing the following:

````php
echo $table->sum(); // sum of table
echo $table->avg(); // average value of table

foreach ($table as $row) {
    echo $row->sum(); // average value of the row
    echo $row->avg(); // min value of row
}

echo $table->getColumn(0)->sum(); // sum of column
````

Grouping
--------

Groups can be used to analyze only certain cells:

````php
 $table = new Table(
     new Row(array(
         new Cell(12, ['group1']),
         new Cell(14, ['group1']),
         new Cell(4, ['group2']),
     )),
 );

 echo $table->sum(['group1']); // 26
 echo $table->sum(['group2']); // 4
````
