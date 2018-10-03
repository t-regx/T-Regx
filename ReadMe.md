<p align="center"><img src="t.regx.png"></p>

# T-Regx | Powerful Regular Expressions library

The most advanced PHP regexp library. Clean, descriptive wrapper functions enhancing PCRE methods. [Scroll to API](#api)

[![Build Status](https://travis-ci.org/Danon/T-Regx.svg?branch=master)](https://travis-ci.org/Danon/T-Regx)
[![Coverage Status](https://coveralls.io/repos/github/Danon/T-Regx/badge.svg?branch=master)](https://coveralls.io/github/Danon/T-Regx?branch=master)
[![Dependencies](https://img.shields.io/badge/dependencies-0-brightgreen.svg)](https://requires.io/github/Danon/T-Regx/requirements/?branch=master)
![Repository Size](https://github-size-badge.herokuapp.com/Danon/T-Regx.svg)
![License](https://img.shields.io/github/license/Danon/T-Regx.svg)
![GitHub last commit](https://img.shields.io/github/last-commit/Danon/T-Regx.svg)
![GitHub commit activity](https://img.shields.io/github/commit-activity/y/Danon/T-Regx.svg)

[![PHP Version](https://img.shields.io/badge/PHP-5.3%2B-blue.svg)](https://github.com/Danon/T-Regx/branches/all)
[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-blue.svg)](https://github.com/Danon/T-Regx/branches/all)
[![PHP Version](https://img.shields.io/badge/PHP-7.1.3%2B-blue.svg)](https://github.com/Danon/T-Regx/branches/all)
[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)

1. [Quick Examples](#quick-examples)
2. [Overview](#overview)
    * [Why T-Regx stands out?](#why-t-regx-stands-out)
    * [Ways of using T-Regx](#ways-of-using-t-regx)
    * [Converting warnings to Exceptions](#saferegex)
3. [Installation](#installation)
    * [Composer](#installation)
4. [API](#api)
    * [Matching](#matching)
    * [Retrieving](#get-all-the-matches)
    * [Iterating](#iterating)
    * [Counting](#counting)
    * [Replacing](#replace-strings)
    * [Match control](#controlling-unmatched-subject)
    * [Splitting](#split-a-string)
    * [Filtering](#filter-an-array)
    * [Validating](#validate-pattern)
    * [Delimitering](#delimiter-a-pattern)
    * [Other](#quoting)
5. [What's better?](#whats-better)
6. [Supported PHP versions](#supported-php-versions)
7. [Performance](#performance)

# Quick Examples

```php
$s = 'My phone is 456-232-123';

pattern('\d{3}')->match($s)->first();  // '456'
```

```php
pattern('\d{3}')->match($s)->all();    // ['456', '232', '123']
```

```php
pattern('\d{3}')->match($s)->only(2);  // ['456', '232']
```

:bulb: See more about [`first()`](#get-the-first-match), [`all()`](#get-all-the-matches) and [`only($limit)`](#get-only-few-matches).

#### Replacing

```php
pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way')->all()->with('__');

// 'P. Sh__man, 42 Wall__y w__'
```

Of course you can also use [`first()`](#replace-strings) and [`only($limit)`](#replace-only-few) to limit your [replacements](#replace-strings).

```php
pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way')->first()->callback(function (Match $m) {
    return '<' . strtoupper($m->text()) . '>';
});

// 'P. Sh<ER>man, 42 Wallaby way'
```

You can even pass bare callbacks!

```php
pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way')->first()->callback('strtoupper');

// 'P. ShERman, 42 Wallaby way'
```

### Advanced examples

Not sure if your pattern is matched or not?

```php
$result = pattern('word')->match($text)
  ->forFirst('strtoupper')->orThrow(InvalidArgumentException::class);  // you can pass any \Throwable class name

$result   // 'WORD'
```

:bulb: Instead of [`orThrow()`](#forFirst-orThrow), you can also use [`orReturn(var)`](#forFirst-orReturn) to pass a default value
or use [`orElse(callback)`](#controlling-unmatched-subject) to fetch value from a callback.

### Details

```php
$p = '(?<value>\d+)(?<unit>cm|mm)';
$s = '192mm and 168cm or 18mm and 12cm';

pattern($p) ->match($s) ->iterate(function (Match $match) {
    
    $match->text();                   // '168cm'
    (string) $match;                  // '168cm'

    (string) $match->group('value');  // '168'
    (string) $match->group(2);        // 'cm'
    $match->offset();                 //  10       UTF-8 safe offset

    $match->group('unit')->text()     // '168'
    $match->group('unit')->offset()   // 13
    $match->group('unit')->index()    // 2
    $match->group(2)->name()          // 'unit'

    $match->groups();                 // ['168', 'cm']
    $match->namedGroups();            // ['value' => '168', 'unit' => 'cm']
    $match->groupNames();             // ['value', 'unit']
    $match->hasGroup('val');          // false

    $match->subject();                // '192mm and 168cm or 18mm and 12cm'
    $match->all();                    // ['192mm', '168cm', '18mm', '12cm']
    $match->group('value')->all();    // ['192', '168', '18', '12']
    $match->group('unit')->all();     // ['mm', 'cm', 'mm', 'cm']
});
```

:bulb: Scroll to [`Match`](#first-match-with-details). 

```php
$p = '(?<value>\d+)(?<unit>cm|mm)';
$s = '192mm and 168cm or 18mm and 12cm';

pattern($p)->match($s)->all()                     // ['192mm', '168cm', '18mm', '12cm']
pattern($p)->match($s)->first()                   // '192mm'

pattern($p)->match($s)->group('value')->all()     // ['192', '168', '18', '12']
pattern($p)->match($s)->group('value')->first()   // '192'
```

You can pass any `callable` and `\Closure` to the `->first()` method. It's result will be returned.

```php
pattern($p)->match($s)->first();                 // '192mm'
pattern($p)->match($s)->first('str_split');      // ['1', '9', '2', 'm', 'm']
pattern($p)->match($s)->first('strlen')          // 5
```

# Overview

## Why T-Regx stands out?

[Scroll to API](#api)

* ### Written with clean API
   * Not even touching your error handlers **in any way**.
   * Descriptive interface
   * `SRP methods`, `UTF-8 support`
   * `No varargs`, `No flags`,  `No boolean arguments`, `No nested arrays`, `No Reflection used`

* ### Working **with** the developer
   * Converts all PCRE notices/error/warnings to exceptions
   * Tracking offset and subjects while replacing strings
   * Fixing error with multi-byte offset

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory. T-Regx's smart delimiter
  will [conveniently add one of many delimiters](#delimiter-a-pattern) for you, if they're not already present.

* ### Converting Warnings to Exceptions
   * Warning or errors during `preg::` are converted to exceptions.
   * `preg_()` can never fail, because it throws `SafeRegexException` on warning/error.
   * In some cases, `preg_()` methods might fail, return `false`/`null` and **NOT** trigger a warning. Separate exception,
     `SuspectedReturnSafeRegexException` is then thrown by T-Regx.
 
[Scroll to API](#api)

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
// Separate API for preg_*() methods
preg::match('/\w+/', $subject);
preg::match_all('/\w+/', $subject);
preg::replace('/\w+/', $replacement, $subject);
preg::replace_callback('/\w+/', $callback, $subject);
// all preg_ methods
```

[Scroll to API](#api)

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

if (preg::match('/\s+/', $input) === false) { // Never happens
```

The last line never happens, because if match failed (all errors - invalid regex syntax, malformed utf-8 subject, backtrack limit 
exceeded, any other error) - then `SafeRegexException` is thrown. You can `try-catch` it, which is impossible with warnings.

[Scroll to API](#api)

# Installation

```bash
$ composer require rawr/t-regx
```

# API

## Matching

Check if subject matches the pattern:
```php
pattern('[A-Z][a-z]+')->matches('Computer');
```
```
true
```

#### Get all the matches:
```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->all()
```
```
['Robert', 'likes', 'trains']
```

#### Get the first match

```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->first()
```
```
'Robert'
```

#### Get only few matches

```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->only(2)
```
```
['Robert', 'likes']
```

#### Callback for the first match

```php
pattern('[a-z]+$')->match('Robert likes trains')->first(function (Match $match) {
    return $match . ' at ' . $match->offset();
});
```
```
'trains at 13'
```

:bulb: `match()->map()` and `match()->first()` accept arbitrary return types, including `null`.

#### Capturing group from all matches
```php
pattern('(?<hour>\d\d)?:(?<minute>\d\d)')->match('14:15, 16:30, 24:05 or none __:30')->group('hour')->all()
```
```
['14', '16', '24', null]
```

#### Capturing group from the first matches
```php
pattern('(?<hour>\d\d)?:(?<minute>\d\d)')->match('14:15, 16:30, 24:05 or none __:30')->group('hour')->first()
```
```
'14'
```

## Iterating

:bulb: This method should really be named `forEach()`, but prior to PHP 5.6 - you can't use keywords as method names :/

```php
pattern('\d+(?<unit>[ckm]?m))')
    ->match('192cm 168m 172km 14mm')
    ->iterate(function (Match $match) {

        // gets the match
        $match->text();         // (string) '172km'
        (string) $match;        // (string) '172km'

        // gets the match offset
        $match->offset();       // (int) 11

        // gets group
        $match->group('unit');  // (string) 'km'

        // gets other matches
        $match->all();          // (array) ['192cm', '168m', '172km', '14mm']
    });
```

:bulb: `Match` object contains many, many useful information. [Scroll to `Match`](#first-match-with-details) to learn more. 

:bulb: You can use `match()->first(function (Match $m) {})` to invoke the callback only for the first match.

### Making a map

```php
pattern('\d+')
    ->match('192 168 172 14')
    ->map(function (Match $match) {
        if ($match == '168') {
            return null;
        }
        return $match->text() * 2;
    });
```
```
[384, null, 344, 28]
```

:bulb: `Match` object contains many, many useful information. [Scroll to `Match`](#first-match-with-details) to learn more.

:bulb: `match()->map()` and `match()->first()` accept arbitrary return types, including `null`.

## Counting

```php
pattern('[aeiouy]')->count('Computer');
```
```
3
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
'P. Sh__man, 42 Wall__y w__, Sydn__'
```

#### Replace first
```php
pattern('er|ab|ay|ey')->replace($text)->first()->with('__')
```
```
'P. Sh__man, 42 Wallaby way, Sydney'
```

#### Replace only few
```php
pattern('er|ab|ay|ey')->replace($text)->only(2)->with('__')
```
```
'P. Sh__man, 42 Wall__y way, Sydney'
```

:bulb: For more readability, use `replace()->callback()` to render strings with capturing groups.

#### Replace using callbacks

```php
pattern('[A-Z][a-z]+')
    ->replace('Some words are Capitalized, and those will be All Caps')
    ->all()
    ->callback('strtoupper');
```
```
'SOME words are CAPITALIZED, and those will be ALL CAPS'
```

#### Replace using callbacks with capturing groups

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
'Links: first and http://second.org.'
```

## Controlling unmatched subject

When you call `pattern('x')->match('asd')->first()` and subject isn't matched by the pattern, then T-Regx throws `SubjectNotMatchedException` that you can catch.

However, if subject is  **expected** not to be matched by the pattern, you can call `forFirst()` and decide whether to return from a callback, throw an exception or return a default value:

* <a name="forFirst-orElse">`forFirst()->orElse(callable)`</a>
  ```php
  pattern('x')
      ->match('asd')
      ->forFirst(function (Match $match) {
          return '*' . $match . '*';
      })
      ->orElse(function (NotMatched $m) {
          return 'Subject ' . $m->subject() . ' unmatched';
      });
  ```
  ```
  'Subject asd unmatched'
  ```

* <a name="forFirst-orReturn">`forFirst()->orReturn()`</a>
  
  You can also just return a default value:
  
  ```php
  pattern('x')
      ->match('asd')
      ->forFirst(function (Match $match) {
          return '*' . $match . '*';
      })
      ->orReturn('Unmatched :/');
  ```
  ```
  'Unmatched :/'
  ```

* <a name="forFirst-orThrow">`forFirst()->orThrow()`</a>

  You can even supply your own exception! It has to implement `\Throwable`, though.
  ```php
  $result = pattern('(x|asd)')
      ->match('asd')
      ->forFirst(function (Match $match) {
          return 'Matched "' . $match . '"';
      })
      ->orThrow(MySuperException::class);
  
  $result; // 'Matched "asd"'
  ```
  It must also have one of the following constructors
  * `__construct()`
  * `__construct($message)`, where `$message` can be a `string`
  * `__construct($message, $subject)`, where `$message` and `subject` can be a `string`

## Split a string

```php
pattern(',')->split('Foo,Bar,Cat')->ex();                // excluding the delimiter

pattern('(\|)')->split('One|Two|Three')->inc();          // including the delimiter

pattern('\.')->split('192..168...18.23')->filter()->ex()  // filtering out empty values
```
```
['Foo', 'Bar', 'Cat']
```
```
['One', '|', 'Two', '|', 'Three']
```
```
['192', '168', '18', '23']
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
['Mark', 'Robert', 'Jane']
```

## Validate pattern

Want to validate a pattern before calling it?
```php
pattern('/[a-z]/')->is()->valid();  // No exceptions, no warnings (no side-effects)
```

| Pattern | regex | Result |
| ------- | ----- | ------ |
| `pattern('/[a-z]/im')->is()->valid()`   | `/[a-z]/im`   | `true`  |
| `pattern('[a-z]+')->is()->valid()`      | `[a-z]+`      | `false` |
| `pattern('//[a-z]')->is()->valid()`     | `//[a-z]`     | `false` |
| `pattern('/(unclosed/')->is()->valid()` | `/(unclosed/` | `false` |

:bulb: Remember that `pattern()->is()->valid()` works with you, so delimiters (`/.*/` or `#.*#`) will not be added automatically 
this time, and won't mess with your input :) If you'd like them to be added though, use `is()->usable()`.

## Delimiter a pattern

Want only to use our awesome delimiterer?

```php
echo pattern('[A-Z]/[a-z]')->delimitered();
echo pattern('[0-9]#[0-9]')->delimitered();
```
```
#[A-Z]/[a-z]#
/[0-9]#[0-9]/
```

### Quoting
```php
echo pattern('Your IP is [192.168.12.20] (local\home)')->quote();   // No exceptions, no warnings (no side-effects)
```
```bash
Your IP is \[192\.168\.12\.20\] \(local\\home\)
```

:bulb: Remember that `pattern()->quote()` doesn't automatically delimiter the pattern (with `/.*/` or `#.*#`).

### First match with details
```php
pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
  ->match('Robert Likes Trains')
  ->first(function (Match $m) {

     $m->text();                          // Gets the match ('Likes')
     (string) $m;                         // alias for text()

     $g = $match->group('capital');
     
     $g->text();                          // get group by name ('L')
     (string) $g;                         // alias 'L'

     $match->group(1)->text();            // get group by index ('L')

     $m->text();                          // 'Likes'
     $m->group('capital')->text()         // value of 'capital' group ('L')
     $m->group('lowercase')->text()       // value of 'lowercase' group ('ikes')
     
     $match->offset();                    // offset of the matched text, utf8 safe (8)
     $m->group('capital')->offset()       // offset of 'capital' group (8)
     $m->group('lowercase')->offset()     // offset of 'lowercase' group (9)

     $m->index();                         // ordinal number of a match in a subject (1)
     $m->group(0)->index()                // 0
     $m->group('capital')->index()        // 1
     $m->group(1)->index()                // 1
     $m->group('lowercase')->index()      // 2
     $m->grouo(2)->index()                // 2
     $m->group(2)->name()                 // 'lowercase'

     $m->group(0)->name()                 // null
     $m->group(1)->name()                 // 'capital'
     $m->group(2)->name()                 // 'lowercase'

     $match->all();                       // ['Robert', 'Likes', 'Trains']
     $match->group('capital')->all();     // ['R', 'L', 'T']
     $match->group('lowercase')->all();   // ['obert', 'ikes', 'trains']

     $match->groups();                    // Gets all group values (['R', 'obert'])                                    
     $match->namedGroups();               // Gets all named groups with values (['capital' => 'R', 'lowercase' => 'obert'])
     $match->groupNames();                // Gets the names of the capturing groups (['capital', 'lowercase'])

     $match->hasGroup('capital');         // Checks whether the group was used in the pattern (true)

     $match->group('capital')->matched(); // Checks whether the group has been matched by subject (true)

     $match->subject();                   // Gets the string that's being searched through ('Robert Likes Trains')
  });
```

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

# Performance

* Unnecessary calls:
  ```php
  pattern('\d+')
      ->match('192 168 172 14')
      ->iterate(function (Match $match) {})
      ->iterate(function (Match $match) {})
      ->iterate(function (Match $match) {})
      ->iterate(function (Match $match) {})
  ```

  T-Regx will perform only one call to `preg_match()`, and use cached results to iterate matches.
* Unnecessary matches:
  ```php
  pattern($p)->match($s)->first()
  pattern($p)->match($s)->only(1)
  ```
  will do only `preg::match()` under the hood, and not `preg::match_all`.
