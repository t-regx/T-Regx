<p align="center"><img src="https://i.imgur.com/AUHFTIX.png"></p>

# T-Regx | Regular Expressions library

The most advanced PHP regexp library. Clean, descriptive wrapper functions enhancing PCRE methods. [Scroll to API](#api)

[![Build Status](https://travis-ci.org/Danon/T-Regx.svg?branch=master)](https://travis-ci.org/Danon/T-Regx)
[![Coverage Status](https://coveralls.io/repos/github/Danon/T-Regx/badge.svg?branch=master)](https://coveralls.io/github/Danon/T-Regx?branch=master)
[![Requirements Status](https://requires.io/github/Danon/T-Regx/requirements.svg?branch=master)](https://requires.io/github/Danon/T-Regx/requirements/?branch=master)
![Repository Size](https://github-size-badge.herokuapp.com/Danon/T-Regx.svg)
[![GitHub](https://img.shields.io/github/license/Danon/T-Regx.svg)](https://github.com/Danon/T-Regx)
[![GitHub last commit](https://img.shields.io/github/last-commit/Danon/T-Regx.svg)](https://github.com/Danon/T-Regx)
[![GitHub commit activity](https://img.shields.io/github/commit-activity/y/Danon/T-Regx.svg)](https://github.com/Danon/T-Regx)

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.3-blue.svg)](https://github.com/Danon/T-Regx/branches/all)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.6-blue.svg)](https://github.com/Danon/T-Regx/branches/all)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.1.3-blue.svg)](https://github.com/Danon/T-Regx)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)

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
6. [Performance](#performance)

# Quick Examples

```php
pattern('\d{3}')->match('My phone is 456-232-123')->all();    // ['456', '232', '123']
```

```php
pattern('\d{3}')->match('My phone is 456-232-123')->first();  //  '456'
```

```php
$result = pattern('word')
  ->match($someDataFromUser)
  ->forFirst('strtoupper')                                   
  ->orThrow(InvalidArgumentException::class);  // you can pass any \Throwable class name

$result   // 'WORD'
```

:bulb: Instead of [`orThrow()`](#forFirst-orThrow), you can also use [`orReturn(var)`](#forFirst-orReturn) to pass a default value
or use [`orElse(callback)`](#controlling-unmatched-subject) to fetch value from a callback.

```php
pattern('(?<value>\d+)(?<unit>cm|mm)')->match('192mm and 168cm 18mm 12cm')->iterate(function (Match $match) {
    
    (string) $match;           // '168cm'
    $match->match();           // '168cm'
    $match->group('value');    // '168'
    $match->group('unit');     // 'cm'
    $match->index();           //  1
    $match->offset();          //  10      UTF-8 safe offset

    $match->subject();         // '192mm and 168cm 18mm 12cm'
    $match->all();             // ['192mm', '168cm', '18mm', '12cm']

    $match->groups();          // ['168', 'cm']
    $match->namedGroups();     // ['value' => '168', 'unit' => 'cm']
    $match->groupNames();      // ['value', 'unit']
    $match->hasGroup('val');   // false
    $match->hasGroup('value'); // true
});
```

:bulb: Scroll to [`Match`](#first-match-with-details). 
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
   * `preg_match()` returns `false` if an error occurred. `preg::match` never returns `false`, because it throws `SafeRegexException` on error.

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
        $match->match();        // (string) '172km'
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
        return $match->match() * 2;
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

However, if subject is  **expected** not to be matched by the pattern, you can call `forFirst()->orElse()`.

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

  You can even supply your own exception! It only has to implement `\Throwable`.
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
  * `__construct($message)`, where `$message` can be `string`
  * `__construct($message, $subject)`, where `$message` and `subject` can be `string`

## Split a string

```php
pattern(',')->split('Foo,Bar,Cat')->ex();                // excluding the delimiter

pattern('(\|)')->split('One|Two|Three')->inc();          // including the delimiter

pattern('.')->split('192..168...18.23')->filter()->ex()  // filtering out empty values
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

     $match->namedGroups();       // Gets all named groups with values (['capital' => 'R', 'lowercase' => 'obert'])

     $match->groupNames();        // Gets the names of the capturing groups (['capital', 'lowercase'])

     $match->hasGroup('capital'); // Checks whether the group was used in the pattern (true)

     $match->matched('capital');  // Checks whether the group has been matched by subject (true)
  });
```

:bulb: `$match->groups()` doesn't return the whole matched string at index 0. To get it, use `$match->match()`.

# What's better
![Ugly api](https://i.imgur.com/g1Buisr.png)

or

![Pretty api](https://i.imgur.com/OW0y0Df.png)

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
