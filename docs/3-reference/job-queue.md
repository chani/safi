# Reference: Asynchronous Job Queue & Worker Daemon

Safi provides a lightweight, database-backed background task execution system managed via `JobQueueService` and the `jobs:worker` CLI command.

---

## 1. Enqueuing Jobs

To dispatch an asynchronous task, call `JobQueueService::push()` with a valid handler class string and payload:

```php
$queue->push(SendEmailHandler::class, [
    'to' => 'user@example.com',
    'template' => 'welcome',
]);
```

Jobs are created with a state of `pending` inside the `job` database table.

---

## 2. Worker Daemon Execution

To consume jobs, execute the worker daemon via the Safi CLI:

```bash
php bin/safi jobs:worker [max_jobs]
```

* **Infinite Loop:** Omitting `max_jobs` runs the process as a persistent daemon.
* **Max Jobs Parameter:** Passing an integer (e.g. `php bin/safi jobs:worker 100`) executes $N$ jobs before exiting cleanly (ideal for Cron execution or systemd task rotation).
* **Retry Limits:** Jobs that throw an unhandled exception are incremented in attempts. Jobs exceeding 3 failed attempts transition to state `buried`.
