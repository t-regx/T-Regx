<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>
<p align="center">
    <a href="https://travis-ci.org/T-Regx/T-Regx"><img src="https://travis-ci.org/T-Regx/T-Regx.svg?branch=master"/></a>
    <a href="https://coveralls.io/github/T-Regx/T-Regx?branch=master"><img src="https://coveralls.io/repos/github/T-Regx/T-Regx/badge.svg?branch=master"/></a>
    <a href="https://github.com/T-Regx/T-Regx/releases"><img src="https://img.shields.io/badge/Stable-v0.9.8-brightgreen.svg?style=popout"/></a>
    <a href="https://github.com/T-Regx/T-Regx"><img src="https://img.shields.io/badge/dependencies-0-brightgreen.svg"/></a>
</p>

# T-Regx | Regular Expressions library

PHP regular expressions brought up to modern standards.

You, choose the interface:
 - I choose to **keep PHP methods** *(but with exceptions, instead of warnings)* ([Scroll to see](#safe-regexps-without-changing-your-api))
 - I choose the **modern regular expression API** ([Scroll to see](#matching))

[See documentation](https://t-regx.com/) at [t-regx.com](https://t-regx.com/).

[![last commit](https://img.shields.io/github/last-commit/T-Regx/T-Regx/develop.svg)](https://github.com/T-Regx/T-Regx/commits/develop)
[![commit activity](https://img.shields.io/github/commit-activity/y/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![Unit tests](https://img.shields.io/badge/Unit%20tests-1511-green.svg)](https://github.com/T-Regx/T-Regx)
[![FQN](https://img.shields.io/badge/FQN-used-blue.svg)](https://github.com/kelunik/fqn-check)
[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-yellow.svg)](https://travis-ci.org/T-Regx/T-Regx "Unofficially, but builds do run on 8.0")


1. [Installation](#installation)
    * [Composer](#installation)
2. [Documentation](#documentation)
3. [Examples](#examples)
    * [Automatic delimiters](#automatic-delimiters)
    * [Matching](#matching)
    * [Replacing](#replacing)
    * [Prepared Patterns](#prepared-patterns)
    * [Optional matches](#optional-matches)
4. [Overview](#overview)
    * [Why T-Regx stands out?](#why-t-regx-stands-out)
    * [Ways of using T-Regx](#ways-of-using-t-regx)
    * [Converting warnings to Exceptions](#converting-warnings-to-exceptions)
5. [Supported PHP versions](#supported-php-versions)
7. [Comparison](#whats-better)
8. [License](#license)

# Installation

Installation for PHP 7.1 and later:

```bash
composer require rawr/t-regx
```

# Documentation

Full API documentation is available at [t-regx.com](https://t-regx.com/).

# Examples

#### Automatic delimiters

These calls are identical:

```php
pattern('\d{3}')->test('123');
```

:bulb: See more about [automatic delimiters](https://t-regx.com/docs/delimiters)

#### Matching

```php
pattern('\d{3}')->match('My phone is 456-232-123')->first();  // '456'
pattern('\d{3}')->match('My phone is 456-232-123')->all();    // ['456', '232', '123']
pattern('\d{3}')->match('My phone is 456-232-123')->only(2);  // ['456', '232']
```

You can pass any `callable` to the [`first()`](https://t-regx.com/docs/match) method:

```php
// first()
pattern('\d{3}')->match('My phone is 456-232-123')->first('str_split');   // ['4', '5', '6']

// map()
pattern('\d{3}')->match('My phone is 456-232-123')->map('strlen');        // [3, 3, 3]
```

:bulb: See more about 
[`first()`](https://t-regx.com/docs/match), 
[`all()`](https://t-regx.com/docs/match),
[`only($limit)`](https://t-regx.com/docs/match) and
[`map()`](https://t-regx.com/docs/match-map), 

#### Replacing

```php
pattern('er|ab|ay') ->replace('P. Sherman, 42 Wallaby way') ->all() ->with('__$1__');

// 'P. Sh__$1__man, 42 Wall__$1__y w__$1__'
```

```php
pattern('W.ll.by') ->replace('P. Sherman, 42 Wallaby way') ->first() ->callback('strtoupper');

// 'P. Sherman, 42 WALLABY way'
```

:bulb: See more about 
[`replace()->with()`](https://t-regx.com/docs/replace-with) / 
[`replace()->withReferences()`](https://t-regx.com/docs/replace-with#regular-expression-references) and 
[`replace()->callback()`](https://t-regx.com/docs/replace-callback).

:bulb: See also:
[`replace()->by()->group()`](https://t-regx.com/docs/replace-by-group) and 
[`replace()->by()->map()`](https://t-regx.com/docs/replace-by-map).

#### Prepared Patterns

Handle unsafe data or user input, in a modern, safe way (no more `preg_quote()`). Think of it as SQL Prepared Statements, but for regexps ;)

```php
Pattern::bind('(You|she) (are|is) @link (yours|hers)', [
    'link' => 'https://t-regx.com/docs/prepared-patterns'
]);
```
or
```php
Pattern::inject('(You|she) (are|is) @ (yours|hers)', ['https://t-regx.com/docs/prepared-patterns']);
```

or even
```php
Pattern::prepare(['(You|she) (are|is) ',  ['https://t-regx.com/docs/prepared-patterns'], ' (yours|hers)']);
```

Above patterns can match both:
```
You are https://t-regx.com/docs/prepared-patterns hers
She is https://t-regx.com/docs/prepared-patterns yours
```

Check out prepared patterns with 
[`Pattern::prepare()`](https://t-regx.com/docs/prepared-patterns#with-pattern-prepare),
[`Pattern::bind()`](https://t-regx.com/docs/prepared-patterns#with-pattern-bind) and
[`Pattern::inject()`](https://t-regx.com/docs/prepared-patterns#with-pattern-inject)

#### Optional matches

Not sure if your pattern is matched or not?

```php
$result = pattern('word')->match('sentence')
  ->findFirst('strtoupper')
  ->orElse(function (NotMatched $notMatched) {
    return "Match not found in " . $notMatched->subject();
  });

$result   // 'Match not found in sentence'
```

:bulb: See more about 
[`orElse(callback)`](https://t-regx.com/docs/match-for-first),
[`orReturn(var)`](https://t-regx.com/docs/match-for-first) or
[`orThrow()`](https://t-regx.com/docs/match-for-first).

# Overview

## Why T-Regx stands out?

:bulb: [See documentation at t-regx.com](https://t-regx.com/)

* ### Working **with** the developer
  * Errors:
    * Not even touching your error handlers **in any way**
    * Converts all PCRE notices/error/warnings to exceptions
    * Calling `preg_last_error()` after each call, to validate your method
    * Warnings/errors don't interfere with each other
    * Not letting fatal errors happen
  * Strings:
    * Tracking offset and subjects while replacing strings
    * Fixing error with multi-byte offset (utf-8 safe)

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory. T-Regx's smart delimiter
  will [conveniently add one of many delimiters](https://t-regx.com/docs/delimiters) for you.

* ### Converting Warnings to Exceptions
   * Warning or errors during `preg::` are converted to exceptions.
   * `preg_()` can never fail, because it throws `PregException` on warning/error.
   * In some cases, `preg_()` methods might fail, return `false`/`null` and **NOT** trigger a warning. Separate exception,
     `SuspectedReturnPregException` is then thrown by T-Regx.

* ### Written with clean API
   * Descriptive interface
   * `SRP methods`, `UTF-8 support`
   * `No Reflection used`, `No (...varargs)`, `No (boolean arguments, true)`, `(No flags, 1)`, `[No [nested, [arrays]]]`

* ### Protects your from fatal errors
   * Certain arguments cause fatal errors with `preg_()` methods.
   * T-Regx will throw a catchable exception, instead of a Fatal Error.

## Ways of using T-Regx

```php
// Static method style
use TRegx\CleanRegex\Pattern;

Pattern::of('[A-Z][a-z]+')->matches('Foo Bar');
```
```php
// Global function style
pattern('[A-Z][a-z]+')->matches('Foo Bar');
```
```php
// Old-school pattern
use TRegx\CleanRegex\Pattern;

Pattern::pcre('/[A-Z][a-z]+/i')->matches('Foo Bar');
```

:bulb: See more about [entry points](https://t-regx.com/docs/introduction) and 
[`pattern()`](https://t-regx.com/docs/introduction).

## Safe regexps without changing your API?

Would you like to protect yourself from any notices, errors, fatal errors and warnings; and use exceptions instead?

Just swap `preg_` to `preg::`! *All warnings and errors are converted to exceptions!*

```php
try {
    if (preg::match_all('/^https?:\/\/(www)?\./', $url) > 0) {
    }

    return preg::replace_callback('/(regexp/i', $myCallback, 'I very much like regexps');
}
catch (PregException $e) {
    $e->getMessage(); // `preg_replace_callback(): missing ) at offset 7`
}

if (preg::match('/\s+/', $input) === false) {
    // Never happens
}
```

`preg::` is an exact copy of `preg_` methods, but catches all warnings, exceptions and calls `preg_last_error()` after each call.

The last line never happens, because if match failed (invalid regex syntax, malformed utf-8 subject, backtrack limit 
exceeded, any other error) - then `SafeRegexException` is thrown.

# Supported PHP versions

Continuous integration builds are running for:

 - `PHP 7.1` (`7.1.0`, `7.1.12`, `7.1.13`, `7.1.27`)
 - `PHP 7.2` (`7.2.0`, `7.2.15`, `7.2.28`)
 - `PHP 7.3` (`7.3.0`, `7.3.6`, `7.3.15`)
 - `PHP 7.4` (`7.4.0`, `7.4.3`)
 - `PHP 8.0` (nightly)

# What's better
![Ugly api](https://t-regx.com/img/external/readme/preg.png)

or

![Pretty api](https://t-regx.com/img/external/readme/t-regx.png)

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License
T-Regx is [MIT licensed](./LICENSE).
