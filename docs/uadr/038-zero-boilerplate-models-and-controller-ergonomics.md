# µADR-038: Zero-Boilerplate Domain Models and Ergonomic Response Helpers
-----
tags: #models #property-hooks #controller #dx #architecture
status: accepted
context: Traditional getter/setter methods in domain models introduce verbose boilerplate code. Additionally, manually encoding query parameters in redirects or setting repetitive HTTP headers in controllers violates DRY principles.
decisions:
  - Domain models encapsulating ORM entities MUST utilize native PHP 8.4+ Property Hooks (`public string $property { get => ...; set => ...; }`) or constructor property promotion instead of getter/setter methods.
  - The base `AbstractController` MUST handle query parameter string assembly internally in `redirect(string $url, array$params = [])`.
  - The base `AbstractController` MUST provide lightweight response helper methods (`html()`, `jsonResponse()`) with pre-configured UTF-8 content-type headers.
consequences:
  - Eliminates boilerplate methods across domain model definitions.
  - Improves developer experience (DX) while keeping application controllers clean and readable.
