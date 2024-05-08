<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>

# T-Regx | Regular Expressions library

Simple library for regular expressions in PHP.

[![OS Arch](https://img.shields.io/badge/OS-32&hyphen;bit-brightgreen.svg)](https://github.com/t-regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-64&hyphen;bit-brightgreen.svg)](https://github.com/t-regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Windows-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Linux/Unix-blue.svg)](https://github.com/t-regx/T-Regx/actions)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://github.com/t-regx/T-Regx/actions)

1. [Installation](#installation)
    * [Composer](#installation)
2. [Examples](#examples)

9. [Sponsors](#sponsors)
10. [License](#license)

[Buy me a coffee!](https://www.buymeacoffee.com/danielwilkowski)

# Installation

You can install the alpha version:

```bash
composer require rawr/t-regx@1.0.0-alpha
```

# Examples

Predicate a string against a regular expression:
```php
$pattern = new Pattern('[a-z]', 'i');
if ($pattern->test($string)) {

}
```

Match a string against a regular expression:
```php
$pattern = new Pattern('(?<group>[a-z])', 'i');

/** @var Detail $match */
$match = $pattern->first($words);  // execute regular expression

$match->text();   // (string) matched text
$match->group(0); // (string) matched text

$match->group(1);        // (string) capturing group
$match->groupOrNull(1);  // (string) capturing group

$match->group('group');  // capturing group by name

$match->offset();        // (int) match position (in unicode characters)
$match->byteOffset();    // (int) match position (in bytes/ascii)
```

Split string by regular expression:

```php
$pattern = new Pattern('[,; ]');

$pieces = $pattern->split("Valar Morghulis"); // ["Valar", "Morghulis"]
```

Replace by regular expression

```php
$pattern = new Pattern('\d+');

$pattern->replace("I have $3", '10');
$pattern->replaceCallback("I have $3", fn(Detail $match) => '10');
```

# Error handling

```php
new Pattern('€[ą-ęA-Z]+++', 'u')
```
```
Regex\SyntaxException : Quantifier does not follow a repeatable item, near position 15.

'€[ą-ęA-Z]+++'
            ^
```


# Sponsors

- [Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)
- [BarxizePL](https://github.com/BarxizePL) - Thanks!

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License

T-Regx is [MIT licensed](LICENSE).
