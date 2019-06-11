<p align="center"><a href="https://t-regx.com/"><img src="t.regx.png"></a></p>

# T-Regx | Powerful Regular Expressions library

The most advanced PHP regexp library. Clean, descriptive, fast wrapper functions enhancing PCRE methods. 
[See documentation](https://t-regx.com/).

[![Build Status](https://travis-ci.org/T-Regx/T-Regx.svg?branch=master)](https://travis-ci.org/T-Regx/T-Regx)
[![Coverage Status](https://coveralls.io/repos/github/T-Regx/T-Regx/badge.svg?branch=master)](https://coveralls.io/github/T-Regx/T-Regx?branch=master)
[![Dependencies](https://img.shields.io/badge/dependencies-0-brightgreen.svg)](https://github.com/T-Regx/T-Regx)
[![Repository Size](https://github-size-badge.herokuapp.com/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![License](https://img.shields.io/github/license/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![GitHub last commit](https://img.shields.io/github/last-commit/T-Regx/T-Regx/develop.svg)](https://github.com/T-Regx/T-Regx/commits/develop)
[![GitHub commit activity](https://img.shields.io/github/commit-activity/y/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![Composer lock](https://img.shields.io/badge/.lock-uncommited-green.svg)](https://github.com/T-Regx/T-Regx)
![PHP Version](https://img.shields.io/badge/Unit%20tests-1012-green.svg)

[![PHP Version](https://img.shields.io/badge/PHP-5.3%2B-blue.svg)](https://packagist.org/packages/rawr/t-regx)
[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-blue.svg)](https://packagist.org/packages/rawr/t-regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://travis-ci.org/T-Regx/T-Regx)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-yellow.svg)](https://travis-ci.org/T-Regx/T-Regx "Unofficially, but builds do run on 8.0")

[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)

1. [Installation](#installation)
    * [Composer](#installation)
2. [API](#api)
3. [Quick Examples](#quick-examples)
4. [Overview](#overview)
    * [Why T-Regx stands out?](#why-t-regx-stands-out)
    * [Ways of using T-Regx](#ways-of-using-t-regx)
    * [Converting warnings to Exceptions](#saferegex)
5. [What's better?](#whats-better)
6. [Supported PHP versions](#supported-php-versions)
7. [Comparison](#whats-better)

# Installation

Installation for PHP 7.1 and later:

```bash
$ composer require rawr/t-regx
```

:bulb: See documentation if you need to use [earlier versions of PHP](https://t-regx.com/docs/installation).

# API

Full API documentation is available at [t-regx.com](https://t-regx.com/).

# Quick Examples

```php
$s = 'My phone is 456-232-123';

pattern('\d{3}')->match($s)->first();  // '456'
pattern('\d{3}')->match($s)->all();    // ['456', '232', '123']
pattern('\d{3}')->match($s)->only(2);  // ['456', '232']
```

You can pass any `callable` to the [`first()`](https://t-regx.com/docs/match) method:

```php
pattern('\d{3}')->match($s)->first('str_split');   // ['4', '5', '6']
pattern('\d{3}')->match($s)->first('strlen')       // 3
```

:bulb: See more about 
[`first()`](https://t-regx.com/docs/match), 
[`all()`](https://t-regx.com/docs/match) and 
[`only($limit)`](https://t-regx.com/docs/match).

#### Replacing

```php
pattern('er|ab|ay')
    ->replace('P. Sherman, 42 Wallaby way')
    ->all()
    ->with('__');

// 'P. Sh__man, 42 Wall__y w__'
```

```php
pattern('er|ab|ay')
    ->replace('P. Sherman, 42 Wallaby way')
    ->first()
    ->callback('strtoupper');

// 'P. ShERman, 42 Wallaby way'
```

:bulb: See more about 
[`replace()->with()`](https://t-regx.com/docs/replace) and 
[`replace()->callback()`](https://t-regx.com/docs/replace-callback).

#### Optional matches

Not sure if your pattern is matched or not?

```php
$result = pattern('word')->match($text)
  ->forFirst('strtoupper')
  ->orThrow(InvalidArgumentException::class);

$result   // 'WORD'
```

:bulb: See more about 
[`orThrow()`](https://t-regx.com/docs/match-for-first), 
[`orElse(callback)`](https://t-regx.com/docs/match-for-first) or 
[`orReturn(var)`](https://t-regx.com/docs/match-for-first).

# Overview

## Why T-Regx stands out?

:bulb: [See documentation](https://t-regx.com/)

* ### Working **with** the developer
   * Not even touching your error handlers **in any way**
   * Converts all PCRE notices/error/warnings to exceptions
   * Tracking offset and subjects while replacing strings
   * Fixing error with multi-byte offset (utf-8 safe)

* ### Written with clean API
   * Descriptive interface
   * `SRP methods`, `UTF-8 support`
   * `No varargs`, `No flags`,  `No boolean arguments`, `No nested arrays`, `No Reflection used`

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory. T-Regx's smart delimiter
  will [conveniently add one of many delimiters](https://t-regx.com/docs/delimiters) for you, if they're not already present.

* ### Converting Warnings to Exceptions
   * Warning or errors during `preg::` are converted to exceptions.
   * `preg_()` can never fail, because it throws `SafeRegexException` on warning/error.
   * In some cases, `preg_()` methods might fail, return `false`/`null` and **NOT** trigger a warning. Separate exception,
     `SuspectedReturnSafeRegexException` is then thrown by T-Regx.

## Ways of using T-Regx

```php
// Facade style
use TRegx\CleanRegex\Pattern;

Pattern::of('[A-Z][a-z]+')->matches($subject)
```
```php
// Global method style
pattern('[A-Z][a-z]+')->matches($subject)
```
```php
// Separate API for preg_*() methods, that catches warnings and throws Exceptions
preg::match('/\w+/', $subject);
preg::match_all('/\w+/', $subject);
preg::replace('/\w+/', $replacement, $subject);
preg::replace_callback('/\w+/', $callback, $subject);
// all preg_ methods
```

:bulb: See more about [entry points](https://t-regx.com/docs/introduction) and 
[`pattern()`](https://t-regx.com/docs/introduction).

## SafeRegex

Just swap `preg_` to `preg::` and yay! All warnings and errors are converted to exceptions!

```php
try {
    if (preg::match_all('/^https?:\/\/(www)?\./', $url) > 0) {
    }

    return preg::replace_callback('/(regexp/i', $myCallback, 'I very much like regexps');
}
catch (SafeRegexException $e) {
    $e->getMessage(); // `preg_replace_callback(): Compilation failed: missing ) at offset 7`
}

if (preg::match('/\s+/', $input) === false) {
    // Never happens
}
```

The last line never happens, because if match failed (invalid regex syntax, malformed utf-8 subject, backtrack limit 
exceeded, any other error) - then `SafeRegexException` is thrown.

You can `try/catch` it, which is impossible with warnings.

# Supported PHP versions

T-Regx has 2 production branches: `master` and `master-php5.3`. As you might expect, `master` is the most recent
release. Ever so often `master` is being merged `master-php5.3` and the most recent changes are also available for PHP `5.3+` - `< 7.1.0`.

 - `master-php5.3` runs on `PHP 5.3` - it just works
 - `master` runs on `PHP 7.1.3` - with`scalar params`, `nullable types`, `return type hints`, `PREG_EMPTY_AS_NULL`, `error_clear_last()`, `preg_replace_callback_array`, etc.

Continuous integration builds are running for:

 - `PHP 5.3.0`, `PHP 5.3.29` (oldest and most recent)
 - `PHP 5.4.45` (newest)
 - `PHP 5.5.38` (newest)
 - `PHP 5.6.24` (newest)
 - `PHP 7.0.3`, `PHP 7.0.31` (oldest and most recent)
 - `PHP 7.1.12`, `PHP 7.1.13`, `PHP 7.1.21`
 - `PHP 7.2.9` (newest)

# What's better
![Ugly api](https://i.imgur.com/g1Buisr.png)

or

![Pretty api](https://i.imgur.com/OW0y0Df.png)
