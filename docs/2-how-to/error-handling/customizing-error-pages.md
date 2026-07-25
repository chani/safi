# How-To: Customizing Error Pages and Handling Exceptions

Configure domain error templates and throw application exceptions.

---

## Triggering Validation Exceptions

Throw `Safi\Core\Exception\ValidationException` to return HTTP 403 Forbidden responses automatically:

```php
use Safi\Core\Exception\ValidationException;

if ($invalid) {
    throw new ValidationException('Access denied due to invalid credentials.');
}
```

---

## Customizing Error Views

Edit `templates/errors/error.twig` to customize non-XHR error layouts:

```twig
{% extends "_page.twig" %}

{% block title %}Error {{ code }}{% endblock %}

{% block content %}
<article>
  <header>
    <h1>{{ code }} {{ title }}</h1>
  </header>
  <p>{{ message }}</p>
</article>
{% endblock %}
```
