# Regular Expressions extension

Clean, descriptive wrapper functions enhancing PCRE extension methods.


## What happens if you fail?
To check whether the pattern fails, you need to change this:
```php
if (preg_match( '/((Hello, )?World/', $word )) {
```

to this:

```php
$result = preg_match('/((Hello, )?World/');

if ($result === false) {
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
  Surrounding `/pattern/` slashes or `~patttern~` tildes are not compulsory. 
  
## API

#### Matching

Checks if subject "contains" the pattern:
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
  0 => string '192',
  1 => string '168',
  2 => string '172',
  3 => string '14',
}
```

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
        $match->index()    // (int) 3

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

#### Replace strings using callbacks

```php
pattern('[A-Z][a-z]+')
  ->replace('Some words are Capitalized, and those will be All Caps')
  ->callback(function ($match) {
    return strtoupper($match);
  });
```
```
(string) 'SOME words are CAPITALIZED and those will be ALL CAPS'
```

# What's better
![GitHub Logo](php.api.png)

or

![GitHub Logo](clean.api.png)
