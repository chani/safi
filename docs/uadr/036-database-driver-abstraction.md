# µADR-036: Database Driver Abstraction in Extensions
-----
tags: #db #abstraction #architecture
status: accepted
context: Extension modules (such as `safi-auth`) must remain storage-agnostic.
decisions:
  - Route all extension persistence operations strictly through `DatabaseDriverInterface` and `ModelInterface`.
consequences:
  - Extension packages can be used with any compliant database driver.
