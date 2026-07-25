# Components vs. Extensions

Safi distinguishes between business application modules (Components) and infrastructure driver packages (Extensions).

---

## Comparison Matrix

| Property | Component | Extension |
| :--- | :--- | :--- |
| **Purpose** | Business domain logic & UI features | Infrastructure, drivers, system integrations |
| **Location** | `components/` directory | `vendor/` directory (Composer package) |
| **Contents** | Controllers, Models, Twig Views, Routes | ServiceProviders, Contracts, Middlewares |
| **Discovery** | Automatic directory scanning via `ComponentManager` | Explicit `bootProviders()` call in `init.inc.php` |
| **Namespace** | `Components\<ComponentName>\...` | `Safi\Extensions\<ExtensionName>\...` |

---

## Decision Tree

1. **Building a business feature (e.g., Invoicing, User Directory, Product Catalog)?**
   -> Create a **Component** in `components/<ComponentName>/`.

2. **Integrating a driver or framework capability (e.g., Cache driver, Router, Template engine)?**
   -> Create an **Extension** in an isolated Repository / Composer package implementing `ServiceProviderInterface`.
