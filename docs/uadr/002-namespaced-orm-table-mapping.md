# µADR-002: Namespaced CamelCase Table Mapping for RedBeanPHP
-----
tags: orm redbeanphp sqlite database
status: accepted

## Context
RedBeanPHP lacks native namespace support, creating table collision risks between isolated components.

## Decision
- Table translation is encapsulated strictly inside safi-db-redbean via getTableName().
- Component models receive a 'comp' prefix followed by ComponentName and ModelName.
- Core contracts remain completely agnostic of database table naming rules.

## Guardrail / Consequences
Raw table name strings must never be passed directly to ORM persistence calls.
