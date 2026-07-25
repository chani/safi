# Tutorial: Creating Your First Component

This step-by-step tutorial guides you through building a standalone `Blog` component containing a Controller, Model, and Twig View.

---

## Step 1: Create Component Directory Structure

Create the directory layout inside `components/Blog`:

```bash
mkdir -p components/Blog/Controllers
mkdir -p components/Blog/Models
mkdir -p components/Blog/Views
```

---

## Step 2: Define the Model

Create `components/Blog/Models/Post.php` using PHP 8.5 Property Hooks:

```php
<?php

declare(strict_types=1);

namespace Components\Blog\Models;

use Safi\Core\Contracts\ModelInterface;

final class Post implements ModelInterface
{
    public function __construct(private readonly mixed $entity = null) {}

    #[\Override]
    public function unwrap(): mixed
    {
        return $this->entity;
    }

    #[\Override]
    public function getId(): int
    {
        $id = $this->getProperty('id', 0);
        return is_numeric($id) ? (int) $id : 0;
    }

    public string $title {
        get => is_string($v = $this->getProperty('title', '')) ? $v : '';
        set => $this->setProperty('title', trim($value));
    }

    private function getProperty(string $property, mixed $default = null): mixed
    {
        return is_object($this->entity) ? ($this->entity->{$property} ?? $default) : $default;
    }

    private function setProperty(string $property, mixed $value): void
    {
        if (is_object($this->entity)) {
            $this->entity->{$property} = $value;
        }
    }
}
```

---

## Step 3: Create the Controller

Create `components/Blog/Controllers/PostController.php`:

```php
<?php

declare(strict_types=1);

namespace Components\Blog\Controllers;

use Components\Blog\Models\Post;
use Safi\Core\AbstractController;
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;

final class PostController extends AbstractController
{
    #[Route('/blog', method: 'GET', name: 'blog.index', public: true)]
    public function index(): Response
    {
        $posts = $this->db->findModels(Post::class, 'ORDER BY id DESC');

        return $this->render('@Blog/index.twig', [
            'title' => 'Blog Posts',
            'posts' => $posts,
        ]);
    }
}
```

---

## Step 4: Create the View Template

Create `components/Blog/Views/index.twig`:

```twig
{% extends "_page.twig" %}

{% block title %}{{ title }}{% endblock %}

{% block content %}
<article>
  <header>
    <h2>Blog Index</h2>
  </header>

  <ul>
    {% for post in posts %}
      <li>{{ post.title }}</li>
    {% else %}
      <li>No posts found.</li>
    {% endfor %}
  </ul>
</article>
{% endblock %}
```

---

## Step 5: Register View Namespace in Composition Root

Open `init.inc.php` and register the component view namespace:

```php
$viewEngine->registerNamespace('Blog', __DIR__ . '/components/Blog/Views');
```

Navigate to `http://localhost:8000/blog` to verify the component.
