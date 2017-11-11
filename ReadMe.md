# Regular Expressions wrapper

Clean, descriptive wrapper functions enhancing PCRE extension methods.

1. [Overview](#regular-expressions-wrapper)
    * [What happens if you fail?](#what-happens-if-you-fail)
    * [Why CleanRegex?](#why-cleanregex)
2. [Installation](#installation)  
3. [API](#api)  
    * [Matching](#matching)
    * [Retrieving](#retrieving)
    * [Iterating](#iterating)
    * [Replacing](#replace-strings)
    * [Other](#first-match-with-callback)
4. [Performance](#performance)

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

## Why CleanRegex?

* ###  Always an exception
  `preg_match()` returns `false` if an error occurred or, if no match is found - `0` (which evaluates to `false`).  You have to do an **explicit check** in order to react to it. CleanRegex always throws an exception. 

  We got your back.

* ### No type-mixing
  Using `PCRE_CAPTURE_OFFSET` changes return types from `string` to an `array`. And there's more...

  You know these. You've been there.

* ### Cleaner API

  CleanRegex allows you to use cleaner, more descriptive and chainable API:

  ```php
  pattern('[a-z0-9]')->replace('Hello, world')->with('*')
  ```
  
* ### Don't have to use /word/ slashes
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory. CleanRegex will add them, if they're not already present. 


# Installation

```bash
composer require "danon/clean-regex"
```


# API

## Matching

Check if subject matches the pattern:
```php
pattern('[aeiouy]')->matches('Computer');
```
```
(bool) true
```

#### Get all matches:
```php
pattern('\d+ ?')->match('192 168 172 14')->all()
```
```
array (4) {
  0 => string '192 ',
  1 => string '168 ',
  2 => string '172 ',
  3 => string '14',
}
```
(without capturing groups)

## Retrieving

Get the first matched part of the string:
```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->first()
```
```
(string) 'Robert'
```

## Iterating
```php
pattern('\d+')
    ->match('192 168 172 14')
    ->iterate(function (Match $match) {

        // gets the match
        $match->match()    // (string) "172"
        (string) $match    // also gets the match

        // gets the match offset 
        $match->offset()  // (int) 8
        
        // gets the match index
        $match->index()    // (int) 2

        // gets other matches
        $match->all()      // (array) [ '192', '168', '172', '14' ]
    });
```

You can also use `match()->first(function (Match $m) {})` to invoke the callback only for the first match.

### Making a map

```php
pattern('\d+')
    ->match('192 168 172 14')
    ->map(function (Match $match) {
        return $match->match() * 2;
    });
```
```
array (4) {
    0 => (integer) 384,
    1 => (integer) 336,
    2 => (integer) 344,
    3 => (integer) 28,
}
```

## Replace strings

```php
pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('*')
```
```
(string) 'P. Sh*man, 42 Wall*y w*, Sydn*'
```

For more readability, use `replace()->callback()` to render strings with capturing groups.

### Replace using callbacks

```php
pattern('[A-Z][a-z]+')
    ->replace('Some words are Capitalized, and those will be All Caps')
    ->callback(function (Match $match) {
        return strtoupper($match);
    });
```
```
(string) 'SOME words are CAPITALIZED and those will be ALL CAPS'
```

### Replace using callbacks with groups

```php
$subject = 'Links: http://google.com and http://other.org.';

pattern('http://(?<name>[a-z]+)\.(com|org)')
    ->replace($subject)
    ->callback(function (Match $match) {
        return $match->group('name');
    });
```
```
(string) 'Links: google and other.'
```

### First match with details
```php
pattern('[a-z]+$')
    ->match('Robert likes trains')
    ->first(function (Match $match) {
        echo $match . ' at ' . $match->offset(); 
    });
```
```bash
trains at 13
```

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
