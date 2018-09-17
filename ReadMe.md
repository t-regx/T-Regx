# Regular Expressions wrapper

The most advanced PHP regexp library. Clean, descriptive wrapper functions enhancing PCRE methods. [Scroll to API](#api)

[![Build Status](https://travis-ci.org/Danon/CleanRegex.svg?branch=master)](https://travis-ci.org/Danon/CleanRegex)
[![Coverage Status](https://coveralls.io/repos/github/Danon/CleanRegex/badge.svg?branch=master)](https://coveralls.io/github/Danon/CleanRegex?branch=master)
[![Requirements Status](https://requires.io/github/Danon/CleanRegex/requirements.svg?branch=master)](https://requires.io/github/Danon/CleanRegex/requirements/?branch=master)
[![Code Quality](https://scrutinizer-ci.com/g/Danon/CleanRegex/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Danon/CleanRegex/?branch=master)
![Size](https://github-size-badge.herokuapp.com/Danon/CleanRegex.svg)
[![GitHub](https://img.shields.io/github/license/Danon/CleanRegex.svg)](https://github.com/Danon/CleanRegex)
[![GitHub last commit](https://img.shields.io/github/last-commit/Danon/CleanRegex.svg)](https://github.com/Danon/CleanRegex)
[![GitHub commit activity](https://img.shields.io/github/commit-activity/y/Danon/CleanRegex.svg)](https://github.com/Danon/CleanRegex)

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.3-blue.svg)](https://github.com/Danon/CleanRegex/branches/all)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.6-blue.svg)](https://github.com/Danon/CleanRegex/branches/all)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.1.3-blue.svg)](https://github.com/Danon/CleanRegex)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)

1. [Overview](#regular-expressions-wrapper)
    * [Why CleanRegex stands out?](#why-cleanregex-stands-out)
    * [What happens if you fail?](#what-happens-if-you-fail)
    * [Ways of using CleanRegex](#ways-of-using-cleanregex)
    * [What's SafeRegex?](#cleanregex-vs-saferegex)
2. [Installation](#installation)
3. [API](#api)  
    * [Matching](#matching)
    * [Retrieving](#get-all-the-matches)
    * [Iterating](#iterating)
    * [Counting](#counting)
    * [Replacing](#replace-strings)
    * [Splitting](#split-a-string)
    * [Filtering](#filter-an-array)
    * [Validating](#validate-pattern)
    * [Delimitering](#delimiter-a-pattern)
    * [Other](#first-match-with-details)
4. [Why CleanRegex stands out?](#why-cleanregex-stands-out)
5. [Performance](#performance)

## Why CleanRegex stands out?

[Scroll to API](#api) 

* ### Written with clean API in mind
   * Not even touching your error handlers **in any way**.
   * Descriptive interface
   * One public method per class - wherever possible
   * SRP methods
   * No varargs
   * No flags or boolean arguments
   * No nested arrays
   * Similar things look similar
   * Different things look different

* ### Working **with** the developer
   * Catches all PCRE-related warnings and throws exceptions instead
   * Additional features that aren't provided by PHP or PCRE.
   * Tracking offset while replacing strings.
   * Pure pattern [validation](#validate-pattern).
   * Protects against **any** PCRE error (not just `preg_last_error()`). See [Exception Tree](https://github.com/PleaseDontKillMe/pattern).
   * Each method throws separate derivation of `CleanRegexException` (`MatchPatternException`, `ReplacePatternException`, etc.), so you can try-catch multiple errors.
   * Handles all PCRE warnings, and throws exceptions instead.

* ### Cleaning the mess after PCRE
   * Fixing error with multi-byte offset.
   * Handling many ways `preg_*()` methods can fail, with graceful exceptions.

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory. CleanRegex's smart delimiter
  will conveniently add one of many delimiters for you (so to minimize the need of you escaping them), if they're not already present.

* ###  Always an exception
   * `preg_match()` returns `false` if an error occurred or, if no match is found - `0` (which evaluates to `false`).  You have to do an **explicit check** to handle the error.
   * CleanRegex **always** throws an exception. 

* ### No type-mixing
  Using `PCRE_CAPTURE_OFFSET` changes return types from `string` to an `array`. And there's more...

  You know these. You've been there.

[Scroll to API](#api)  

## What happens if you fail?
To check whether the pattern fails, you need to change this:
```php
if (preg_match( '/((Hello, )?World/', $word )) {
```

to this:

```php
if (($result = preg_match('/((Hello, )?World/')) === false) {
    throw new Exception();
}

if ($result) {
```
*`preg_match()`  can return `1` (match), `0` (no matches) or `false` (pattern error).*

Awful!

[Scroll to API](#api) 

## Ways of using CleanRegex

```php
// Facade style
\CleanRegex\Pattern::of('[A-Z][a-z]+')->matches($subject)
```
```php
// Global method style
pattern('[A-Z][a-z]+')->matches($subject)
```
```php
// Separate API for preg_*() methods
preg::match('/\w+/', $subject);
preg::match_all('/\w+/', $subject);
preg::replace('/\w+/', $replacement, $subject);
preg::replace_callback('/\w+/', $callback, $subject);
// and so on...
```

[Scroll to API](#api)  

## CleanRegex vs SafeRegex

Only interested in catching warnings and fails, without changing your code?

```php
use SafeRegex\preg;

$result = preg::match('/a/', $subject'); // idential to preg_match, but never emits a warning or returns false
```

SafeRegex is an exact copy of `preg_*()` functions, and:
 * Exactly alike interface
 * Never emit warnings
 * If an error occurred, they throw an exception
 * You don't need to worry about warnings or returning `false` 
 ([or sometimes null](http://php.net/manual/en/function.preg-replace-callback-array.php)) - results that suggest that the 
 method failed.

Regardless, of whether you use `preg_match_all()` or `preg::match_all()`, these methods have **exactly** alike interfaces and parameters,
and return **exactly** the same data. The only exception is, that SafeRegex methods never emit warnings or return `false` 
([or sometimes null](http://php.net/manual/en/function.preg-replace-callback-array.php)), but throw an exception on fail.

[Scroll to API](#api)  

# Installation
Simply add a composer dependency :)

```bash
composer require "pattern/clean-regex"
```

# API

## Matching

Check if subject matches the pattern:
```php
pattern('[A-Z][a-z]+')->matches('Computer');
```
```
(bool) true
```

#### Get all the matches:
```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->all()
```
```
array (3) {
  0 => string 'Robert',
  1 => string 'likes',
  2 => string 'trains',
}
```

#### Get the first match

```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->first()
```
```
(string) 'Robert'
```

#### Callback for the first match

```php
pattern('[a-z]+$')->match('Robert likes trains')->first(function (Match $match) {
    return $match . ' at ' . $match->offset();
});
```
```
(string) 'trains at 13'
```

#### Callback with arbitrary return type

```php
$result = pattern('[a-zA-Z]+')->match('Robert likes trains')->first(function (Match $match) {
    $name = $match->match();
    return str_split($name);
});
```
```
$this->assertEquals(['R', 'o', 'b', 'e', 'r', 't'], $result);
```

:bulb: `match()->map()` and `match()->first()` accept arbitrary return types, including `null`. 

## Iterating

:bulb: This method should really be named `forEach()`, but prior to PHP 5.6 - you can't use keywords as method names :/

```php
pattern('\d+')
    ->match('192 168 172 14')
    ->iterate(function (Match $match) {

        // gets the match
        $match->match()    // (string) "172"
        (string) $match    // (string) "172"

        // gets the match offset 
        $match->offset()   // (int) 8
        
        // gets the match index
        $match->index()    // (int) 2

        // gets other matches
        $match->all()      // (array) [ '192', '168', '172', '14' ]
    });
```

:bulb: `Match` object contains many, many useful information. [Scroll to Matching with details](#first-match-with-details) to learn more about `Match`. 

:bulb: You can use `match()->first(function (Match $m) {})` to invoke the callback only for the first match.

### Making a map

```php
pattern('\d+')
    ->match('192 168 172 14')
    ->map(function (Match $match) {
        if ($match == '168') {
            return null;
        }
        return $match->match() * 2;
    });
```
```
array (4) {
    0 => (integer) 384,
    1 => null,
    2 => (integer) 344,
    3 => (integer) 28,
}
```

:bulb: `Match` object contains many, many useful information. Learn more about `Match` in [Matching with details](#first-match-with-details)

:bulb: `match()->map()` and `match()->first()` accept arbitrary return types, including `null`.  

## Counting

```php
pattern('[aeiouy]')->count('Computer');
```
```
(int) 3
```

You can get the same effect by calling
```php
pattern('[aeiouy]')->match('Computer')->count();
```

## Replace strings

```php
$text = 'P. Sherman, 42 Wallaby way, Sydney';

pattern('er|ab|ay|ey')->replace($text)->all()->with('__')
```
```
(string) 'P. Sh__man, 42 Wall__y w__, Sydn__'
```

#### Replace first
```php
pattern('er|ab|ay|ey')->replace($text)->first()->with('__')
```
```
(string) 'P. Sh__man, 42 Wallaby way, Sydney'
```

For more readability, use `replace()->callback()` to render strings with capturing groups.

#### Replace using callbacks

```php
pattern('[A-Z][a-z]+')
    ->replace('Some words are Capitalized, and those will be All Caps')
    ->all()
    ->callback(function (Match $match) {
        return strtoupper($match);
    });
```
```
(string) 'SOME words are CAPITALIZED, and those will be ALL CAPS'
```

#### Replace using callbacks with groups

```php
$subject = 'Links: http://first.com and http://second.org.';

pattern('http://(?<name>[a-z]+)\.(com|org)')
    ->replace($subject)
    ->first()
    ->callback(function (Match $match) {
        return $match->group('name');
    });
```
```
(string) 'Links: first and http://second.org.'
```

## Split a string
Split a string:
```php
pattern(',')->split('Foo,Bar,Cat')->split();
```
```
array(3) [
    0 => (string) 'Foo', 
    1 => (string) 'Bar', 
    2 => (string) 'Cat'
]
```

Split a string, but also include a delimiter in the result:
```php
pattern('(,)')->split('One,Two,Three')->separate();
```
```
array(3) [
    0 => (string) 'One', 
    1 => (string) ',', 
    2 => (string) 'Two', 
    2 => (string) ',', 
    4 => (string) 'Three'
]
```

## Filter an array

```php
pattern('[A-Z][a-z]+$')->filter([
     'Mark',
     'Robert',
     'asdczx',
     'Jane',
     'Stan123'
 ])
```
```
array (3) {
    0 => (string) 'Mark',
    1 => (string) 'Robert',
    2 => (string) 'Jane',
}
```

## Validate pattern

Want to validate a pattern before calling it?
```php
pattern('/[a-z]/')->valid();  // No exceptions, no warnings (no side-effects)
```

| Pattern | Result |
| --- | --- |
| `pattern('/[A-Za-z]/')->valid()`  | `(bool) true`  |
| `pattern('/[a-z]/im')->valid()`   | `(bool) true`  |
| `pattern('//[a-z]')->valid()`     | `(bool) false` |
| `pattern('welcome')->valid()`     | `(bool) false` |
| `pattern('/(unclosed/')->valid()` | `(bool) false` |

:bulb: Remember that `pattern()->valid()` works with you, so delimiters (`/.*/` or `#.*#`) will not be added automatically 
this time, and won't mess with your input :) so you can be sure whether the input pattern is valid or not.

## Delimiter a pattern 

Want only to use our awesome delimiterer?

```php
pattern('[A-Z]/[a-z]')->delimitered();
```
```
#[A-Z]/[a-z]#
```

### Quoting
```php
echo pattern('.*[a-z]?')->quote();   // No exceptions, no warnings (no side-effects)
```
```bash
\.\*\[a\-z\]\?
```

:bulb: Remember that `pattern()->quote()` doesn't automatically delimiter the pattern (with `/.*/` or `#.*#`).

### First match with details
```php
pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
  ->match('Robert Likes Trains')
  ->first(function (Match $match) {

     $match->match();    // Gets the match ('Robert')
     (string) $match;    // alias for match()

     $match->subject();  // Gets the string that's being searched through ('Robert Likes Trains')

     $match->index();    // Ordinal number of the match in the string

     $match->offset();   // Gets the position of the match in the string, UTF8-safe

     $match->all();      // Gets all other matches ('Robert', 'Likes', 'Trains')

     $match->group('capital');    // Gets the value of a capturing group, by name ('R')
     $match->group(2);            // Gets the value of a capturing group, by index ('obert')

     $match->groups();            // Gets all group values (['R', 'obert'])

     $match->groupNames();        // Gets the names of the capturing groups (['capital', 'lowercase'])

     $match->namedGroups();       // Gets all named groups with values (['capital' => 'R', 'lowercase' => 'obert'])

     $match->hasGroup('capital'); // Checks whether the group was used in the pattern (true)

     $match->matched('capital');  // Checks whether the group has been matched by subject (true)
  });
```

:bulb: `$match->groups()` doesn't return the whole matched string at index 0. To get it, use `$match->subject()`.

# What's better
![Ugly api](php.api.png)

or

![Pretty api](clean.api.png)

# Performance

#### Unnecessary calls
```php
pattern('\d+')
    ->match('192 168 172 14')
    ->iterate(function (Match $match) {})
    ->iterate(function (Match $match) {})
    ->iterate(function (Match $match) {})
    ->iterate(function (Match $match) {})
```

Clean Regex will perform only one call to `preg_match()`, and use cached results to iterate matches.
