# µADR-015: Rejected: Native PHP DOM-Morphing Engine

## Context & Decision
Embedding DOM-diffing or JS runtimes inside PHP kernel bloats the framework. HTMX and native browser standards solve HTML-over-the-wire cleanly.

## Rules
- Do: Return standard HTML fragments or Twig templates for dynamic UI requests.
- Don't: Embed JS morphing runtimes inside the core PHP framework.
