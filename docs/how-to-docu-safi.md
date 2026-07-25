# Safi Documentation Standards & Style Guide

This guide defines the writing standards, structural rules, and content constraints for all documentation in the Safi Microframework project.

---

## 1. Structural Architecture: Diátaxis Framework

All documentation files must be categorized into one of four distinct Diátaxis modes based on user intent:

| Mode | User Intent | Primary Focus | Output Style |
| :--- | :--- | :--- | :--- |
| **Tutorials** | Learning | Step-by-step guidance | Hand-holding, complete working code |
| **How-To Guides** | Problem Solving | Executing a specific task | Direct, code-first, goal-oriented |
| **Reference** | Information Lookup | Technical specifications | Precise, dry, API signatures & tables |
| **Explanation** | Understanding | Architectural concepts | Analysis, trade-offs, design rationale |

---

## 2. Content Structuring: Information Mapping

- **Chunking:** Break text into focused, single-topic sections. Limit sections to a maximum of 7 related points.
- **Labeling:** Use explicit, functional headings that describe contents directly. Avoid ambiguous or decorative titles.
- **Relevance:** Exclude non-essential background information from procedural guides. Link to Explanation or Reference documents for conceptual details.

---

## 3. Style & Technical Writing Rules

- **Verb-Driven Headings:** Section headings in Tutorials and How-To guides must start with active imperative verbs.
- **Code-First Layout:** In How-To guides, place the primary code snippet before the explanatory text.
- **Focused Code Snippets:** Include only code relevant to the specific topic.
- **Explicit Type Declarations:** All PHP examples must adhere to PHP 8.5 strict typing standards (`declare(strict_types=1);`).
