# µADR-007: Constant-Memory Binary Stream Piping

## Context & Decision
Loading large binary uploads into memory strings triggers PHP memory limit errors. Stream transfers must pipe data directly via `stream_copy_to_stream()`.

## Rules
- Do: Use stream handles and `pipeRawBody()` for binary file processing.
- Don't: Load binary request bodies or large files into PHP memory strings.
