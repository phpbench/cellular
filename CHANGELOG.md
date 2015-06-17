CHANGELOG
=========

dev-master
----------

### Bugs

- Division by zero error on HHVM

### Features

- Use mergesort algorithm for sorting instead of usort (preserve order)

0.2
---

### Features

- [BC BREAK] Table, Cell, Row and Column math methods removed. Functionality replaced by new 
  `Calculator` class with static methods.
- [BC BREAK] Removal of builders. Elements are mutable by design (for speed).
- [BC BREAK] `Aggregated` class renamed to `Cellular`.
- [BC BREAK] `Cell` no longer extends or implements anything.
- New `Collection` base class providing collection functions: `partition`,
  `aggregate`, `sort`, `evaluate`, `map`, etc.
- `Table`, `Row` are iteratable, countable and array accessible.
- The `avg` function has been renamed to `mean`.
- Added `Workspace` cellular instance which contains instances of `Table`.
- Tables can have titles and descriptions.
- Dropped support for PHP 5.3
- `Cell` and `Cellular` instances now use the `GroupTrait`, which introduces
  the `inGroup` method.
- `Cell` and `Cellular` instances now use the `AttributeTrait`, which allows
  arbitrary attributes to be set on them.
