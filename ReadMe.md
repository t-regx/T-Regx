<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>
<p align="center">
    <a href="https://travis-ci.org/T-Regx/T-Regx"><img src="https://travis-ci.org/T-Regx/T-Regx.svg?branch=master"/></a>
    <a href="https://coveralls.io/github/T-Regx/T-Regx?branch=master"><img src="https://coveralls.io/repos/github/T-Regx/T-Regx/badge.svg?branch=master"/></a>
    <a href="https://github.com/T-Regx/T-Regx/releases"><img src="https://img.shields.io/badge/Stable-v0.9.9-brightgreen.svg?style=popout"/></a>
    <a href="https://github.com/T-Regx/T-Regx"><img src="https://img.shields.io/badge/dependencies-0-brightgreen.svg"/></a>
</p>

# T-Regx | Regular Expressions library

PHP regular expressions brought up to modern standards.

You, choose the interface:
 - I choose to **keep PHP methods** *(but with exceptions, instead of warnings)* ([Scroll to](#no-change-in-api))
 - I choose the **modern regular expression API** ([Scroll to](#written-with-clean-api))

[See documentation](https://t-regx.com/) at [t-regx.com](https://t-regx.com/).

[![last commit](https://img.shields.io/github/last-commit/T-Regx/T-Regx/develop.svg)](https://github.com/T-Regx/T-Regx/commits/develop)
[![commit activity](https://img.shields.io/github/commit-activity/y/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![Unit tests](https://img.shields.io/badge/Unit%20tests-1608-green.svg)](https://github.com/T-Regx/T-Regx)
[![Repository size](https://github-size-badge.herokuapp.com/T-Regx/playground.svg)](https://github.com/T-Regx/T-Regx)
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
3. [Overview](#why-t-regx-stands-out)
4. [Supported PHP versions](#supported-php-versions)
5. [Comparison](#whats-better)
6. [License](#license)

# Installation

Installation for PHP 7.1 and later:

```bash
composer require rawr/t-regx
```

# Documentation

Full API documentation is available at [t-regx.com](https://t-regx.com/).

List of changes is available in [ChangeLog.md](https://github.com/T-Regx/T-Regx/blob/develop/ChangeLog.md).

# Why T-Regx stands out?

:bulb: [See documentation at t-regx.com](https://t-regx.com/)

* ### No change in API!
  * You can use T-Regx safe features and exception-based error handling, without
    changing your API.
    
    Simply swap `preg_match()` to `preg::match()`, and your method is safe! Arguments
    and return types remain the same.

* ### Working **with** the developer
  * Errors:
    * Not even touching your error handlers **in any way**
    * Converts all PCRE notices/error/warnings to exceptions
    * Preventing fatal errors
  * Strings:
    * [Tracking offset](https://t-regx.com/docs/replace-match-details) and subjects while replacing strings
    * [Fixing error with multi-byte offset (utf-8 safe)](https://t-regx.com/docs/match-details#offsets)

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory.

* ### Converting Warnings to Exceptions
   * Warning or errors during `preg::` are converted to exceptions.
   * `preg_()` can never fail, because it throws `PregException` on warning/error.
   * In some cases, `preg_()` methods might fail, return `false`/`null` and **NOT** trigger a warning. Separate exception,
     `SuspectedReturnPregException` is then thrown by T-Regx.

* ### Written with clean API
   * Descriptive interface
   * SRP methods
   * UTF-8 support out-of-the-box
   * `No Reflection used`, `No (...varargs)`, `No (boolean arguments, true)`, `(No flags, 1)`, `[No [nested, [arrays]]]`

* ### Protects your from fatal errors
   Certain arguments cause fatal errors with `preg_()` methods. T-Regx will throw a catchable 
   exception, instead of a Fatal Error.

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

# Sponsors

[Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License
T-Regx is [MIT licensed](./LICENSE).
