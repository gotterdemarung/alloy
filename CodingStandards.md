Coding standards
================

The intent of this guide is to reduce cognitive friction when scanning code 
from different authors. It does so by enumerating a shared set of rules and 
expectations about how to format PHP code.

The style rules herein are derived from commonalities among the various member
projects. When various authors collaborate across multiple projects, it helps
to have one set of guidelines to be used among all those projects. Thus, the
benefit of this guide is not in the rules themselves, but in the sharing of
those rules.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt

1. General
----------

### 1.1 Basic Coding Standard

Code MUST follow Zend coding standard

### 1.2 Files

All PHP files MUST use the Unix LF (linefeed) line ending.

All PHP files MUST end with a single blank line.

The closing `?>` tag MUST be omitted from files containing only PHP.

### 1.3. Lines

Lines SHOULD NOT be longer than 80 characters; lines longer than that SHOULD
be split into multiple subsequent lines of no more than 80 characters each.

### 1.4. Indenting

Code MUST use an indent of 4 spaces, and MUST NOT use tabs for indenting.

### 1.5. Keywords and True/False/Null

PHP [keywords][] MUST be in lower case.

The PHP constants `true`, `false`, and `null` MUST be in lower case.

[keywords]: http://php.net/manual/en/reserved.keywords.php

### 1.6. Commenting

All classes, interfaces and methods MUST have valid phpDoc comment


2. Interfaces
-------------

### 2.1 Naming

All interface names MUST begin with `I`, and MUST NOT contain `Interface` suffix

Example:
```php
interface ISerializeable {} // OK
interface SerializeableInterface{} // wrong
```
