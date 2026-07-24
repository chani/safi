# STYLE-001: Constructor Property Promotion and Early Return Guards
-----
tags: code-style php8 clean-code

## Rule Definition
1. Constructor Property Promotion: All class dependencies and immutable parameters must be declared directly in the constructor signature. Manual property assignments ($this->a =$a) are prohibited for standard injections.
2. Control Flow Flattening: Deeply nested conditional blocks (if/else chains) must be flattened using early return guards or early exception throwing.
3. Explicit Return Types: Every class method and closure must specify an explicit return type hint.
