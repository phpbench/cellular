Data Table
==========

The data table library provides an object oriented way of analyzing tabular data.

Usage
-----

       |Col 1  | Col 2 | Col 3
 ----- | ----- | ----- | -----
 Row1  | 12    | 14    | 4
 Row2  | 12    | 14    | 4

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

All elements implement an aggregateable interface, allowing the following:

````php
echo $table->sum(); // sum of table
echo $table->avg(); // average value of table

foreach ($table as $row) {
    echo $row->sum(); // average value of the row
    echo $row->avg(); // min value of row
}
````
