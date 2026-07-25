# How-To: Validating CSRF Tokens and Security Headers

Protect state-mutating requests against Cross-Site Request Forgery.

---

## CSRF Validation in Controllers

Call `$this->validateCsrf()` inside controller actions handling `POST`, `PUT`, or `DELETE` requests:

```php
#[Route('/settings/update', method: 'POST')]
public function update(): Response
{
    $this->validateCsrf();
    // Process input...
}
```
