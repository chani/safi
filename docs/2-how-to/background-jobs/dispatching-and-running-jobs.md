# How-To: Dispatching Background Jobs and Running Workers

Enqueue background processing jobs and execute worker processes via `JobQueueService`.

---

## Enqueueing a Job

Inject `JobQueueService` and call `push()` with the target handler class name and payload:

```php
use Safi\Core\Services\JobQueueService;

final class InvoiceController extends AbstractController
{
    public function __construct(
        // ...
        private readonly JobQueueService $queue,
    ) {}

    public function generate(int $id): Response
    {
        $this->queue->push(App\Jobs\GeneratePdfJob::class, ['invoice_id' => $id]);
        return $this->jsonResponse(['status' => 'queued']);
    }
}
```

---

## Defining a Job Handler

Create a class with a `handle(array $payload)` method:

```php
namespace App\Jobs;

final class GeneratePdfJob
{
    public function handle(array $payload): void
    {
        $invoiceId = $payload['invoice_id'] ?? 0;
        // Generate PDF logic...
    }
}
```

---

## Running the Worker Daemon

Run the built-in CLI worker daemon from the terminal:

```bash
php bin/safi jobs:worker
```
