# Regular Expressions wrapper

Clean, descriptive wrapper functions enhancing PCRE extension methods.


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
  
## API

#### Matching

Checks if subject matches the pattern:
```php
pattern('[aeiouy]')->matches('Computer');
```
```
(bool) true
```

#### Get all the matches:
```php
pattern('\d+ ?')->match('192 168 172 14')->all()
```
```
array (4) {
  0 => string '192',
  1 => string '168',
  2 => string '172',
  3 => string '14',
}
```
(without capturing groups)

#### Retrieving

Gets the first matched part of the string:
```php
pattern('[a-zA-Z]+')->match('Robert likes trains')->first()
```
```
(string) 'Robert'
```

#### Iterate matches:
```php
pattern('\d+ ?')
    ->match('192 168 172 14')
    ->iterate(function (Match $match) {

        // gets the match
        $match->match()    // (string) "172"
        (string) $match    // also gets the match

        // gets the match offset 
        $match->offset()  // (int) 8
        
        // gets the group index
        $match->index()    // (int) 2

        // gets other groups
        $match->all()      // (array) [ '192', '168', '172', '14' ]

    });
```

#### Replace strings

```php
pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('*')
```
```
(string) 'P. Sh*man, 42 Wall**y w**, Sydn**'
```

Because we value clean API, magic string patterns (`$1`, `${12}`, `\\2`) will not be treated "magically". 
They will be replaced literally. 
```php
pattern('\d+')->replace('600 700 800')->with('Number:$1')
```
```
(string) 'Number:$1 Number:$1 Number:$1'
```

Use `replace()->callback()` to render strings with capturing groups.

#### Replace using callbacks

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

#### Replace using callbacks with groups

```php
pattern('(http|ftp)://(?<host>[a-z]+\.(com|org))')
  ->replace('Links: http://google.com and ftp://some.org.')
  ->callback(function (Match $match) {
    return $match->group('host');
  });
```
```
(string) 'Links: google.com and some.org.'
```

# What's better
![Ugly api](php.api.png)

or

![Pretty api](clean.api.png)

# Performance

#### Unnecessary calls
```php
pattern('\d+ ?')
    ->match('192 168 172 14')
    ->iterate(function (Match $match) {})
    ->iterate(function (Match $match) {})
    ->iterate(function (Match $match) {})
    ->iterate(function (Match $match) {})
```

Clean Regex will perform only one call to `preg_match()`, and use cached results to iterate matches.

#### PCRE magic format

```php
pattern('.*')
    ->replace('Word')
    ->with('Something $1')
```
Will result in `Something $1`, not `Something Word`, because Clean Regex will treat all subjects literally.

However, if you need better performance (using single call to C API, without PHP callbacks) you can us this:
```php
pcre()
    ->pattern('.*')
    ->replace('Word')
    ->withPcreFormat('Something $1')
```
```
(string) 'Something Word' 
```
