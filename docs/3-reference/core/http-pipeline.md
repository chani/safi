# Reference: HTTP Pipeline (`Safi\Core\Http\*`)

The HTTP execution pipeline handles incoming requests, middleware execution, and outgoing responses.

---

## Classes

### `Request`
- `getMethod(): string`: Returns HTTP verb in uppercase.
- `getUri(): string`: Returns decoded URI path without query parameters.
- `get(string $key, mixed$default = null): mixed`: Queries `$_GET` data.
- `post(string $key, mixed$default = null): mixed`: Queries `$_POST` data.
- `isXhr(): bool`: Returns `true` if `X-Requested-With` or `HX-Request` headers are present.

### `Response`
- `getStatus(): int`: Returns HTTP status code.
- `setContent(string $content): void`: Sets payload body.
- `setHeader(string $name, string$value): void`: Sets outgoing header.
- `send(): void`: Emits HTTP headers and prints body content.

### `Context`
Readonly context container passed through `MiddlewareInterface`:
- `public readonly Request $request`
- `public Response $response`
- `public readonly LoggerInterface $logger`
