# How-To: Handling File Uploads and Constant-Memory Streams

Pipe large request payload streams directly to target storage without loading entire buffers into memory.

---

## Streaming Raw Body to File

Use `pipeRawBody()` on `Request` to stream raw binary input:

```php
#[Route('/upload/raw', method: 'POST')]
public function uploadRaw(): Response
{
    $targetFile = fopen(__DIR__ . '/../../data/uploads/file.bin', 'wb');
    if ($targetFile === false) {
        return new Response('Storage error', 500);
    }

    $this->request->pipeRawBody($targetFile);
    
    return $this->jsonResponse(['status' => 'uploaded']);
}
```

---

## Handling Form File Uploads

Query upload file metadata via `getFiles()`:

```php
#[Route('/upload/form', method: 'POST')]
public function uploadForm(): Response
{
    $files = $this->request->getFiles();
    $avatar = $files['avatar'] ?? null;

    if (is_array($avatar) && isset($avatar['tmp_name'])) {
        move_uploaded_file($avatar['tmp_name'], __DIR__ . '/../../data/uploads/avatar.jpg');
    }

    return $this->redirect('/profile');
}
```
