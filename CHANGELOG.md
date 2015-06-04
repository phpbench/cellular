CHANGELOG
=========

0.2
---

- [BC BREAK] Table, Cell, Row and Column math methods removed. Functionality replaced by new 
  `Calculator` class with static methods.
- [BC BREAK] Removal of builders. Elements are mutable by design (for speed).
- [BC BREAK] `Aggregated` class renamed to `Cellular`.
- [BC BREAK] `Cell` no longer extends or implements anything.
- New `Collection` base class providing collection functions: `partition`,
  `fork`, `sort`, `evaluate`, `map`, etc.
- `Table`, `Row` are iteratable, countable and array accessible.
- The `avg` function has been renamed to `mean`.
- Added `Workspace` cellular instance which contains instances of `Table`.
