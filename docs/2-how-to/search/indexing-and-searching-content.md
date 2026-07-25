# How-To: Indexing and Searching Content

Index domain documents and execute full-text queries using `SearchService`.

---

## Indexing Document Entries

```php
use Safi\Core\Services\SearchService;

$searchService->index(
    module: 'wiki',
    title: 'Installation Guide',
    content: 'Full installation documentation text here...',
    url: '/docs/installation'
);
```

---

## Querying the Search Index

```php
$results = $searchService->search('installation');

foreach ($results as $item) {
    // $item['title'], $item['url'], $item['module']
}
```

---

## Reindexing via CLI

Rebuild or clear module search targets:

```bash
php bin/safi search:reindex wiki
```
