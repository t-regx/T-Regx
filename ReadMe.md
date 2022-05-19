<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>
<p align="center">
    <a href="https://github.com/T-Regx/T-Regx/actions/"><img src="https://github.com/T-Regx/T-Regx/workflows/build/badge.svg" alt="Build status"/></a>
    <a href="https://coveralls.io/github/T-Regx/T-Regx"><img src="https://coveralls.io/repos/github/T-Regx/T-Regx/badge.svg" alt="Unit tests"/></a>
    <a href="https://github.com/T-Regx/T-Regx/releases"><img src="https://img.shields.io/badge/latest-0.28.0-brightgreen.svg?style=popout"/></a>
    <a href="https://github.com/T-Regx/T-Regx"><img src="https://img.shields.io/badge/dependencies-0-brightgreen.svg"/></a>
</p>

# T-Regx | Regular Expressions library

PHP regular expressions brought up to modern standards.

[See documentation](https://t-regx.com/) at [t-regx.com](https://t-regx.com/).

[![last commit](https://img.shields.io/github/last-commit/T-Regx/T-Regx/develop.svg)](https://github.com/T-Regx/T-Regx/commits/develop)
[![commit activity](https://img.shields.io/github/commit-activity/y/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![Commits since](https://img.shields.io/github/commits-since/T-Regx/T-Regx/0.28.0/develop.svg)](https://github.com/T-Regx/T-Regx/compare/0.28.0...develop)
[![Unit tests](https://img.shields.io/badge/Unit%20tests-2568-brightgreen.svg)](https://github.com/T-Regx/T-Regx)
[![Code Climate](https://img.shields.io/codeclimate/maintainability/T-Regx/T-Regx.svg)](https://codeclimate.com/github/T-Regx/T-Regx)
[![Repository size](https://img.shields.io/github/languages/code-size/T-Regx/vanilla.svg?label=size)](https://github.com/T-Regx/T-Regx)
[![FQN](https://img.shields.io/badge/FQN-used-blue.svg)](https://github.com/kelunik/fqn-check)
[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)
[![Gitter](https://badges.gitter.im/T-Regx/community.svg)](https://gitter.im/T-Regx/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![OS Arch](https://img.shields.io/badge/OS-32&hyphen;bit-brightgreen.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-64&hyphen;bit-brightgreen.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Windows-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Linux/Unix-blue.svg)](https://github.com/T-Regx/T-Regx/actions)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://github.com/T-Regx/T-Regx/actions)

1. [Installation](#installation)
    * [Composer](#installation)
2. [What T-Regx is and isn't](#what-t-regx-is-and-isnt)
3. [API](#api)
    1. [For legacy projects - `preg::match_all()`](#no-change-in-api-for-legacy-projects)
    2. [For standard projects -`pattern()`](#written-with-clean-api)
4. [Documentation](#documentation)
5. [T-Regx fiddle - Try online](#try-it-online-in-your-browser)
6. [Overview](#why-t-regx-stands-out)
7. [Comparison](#whats-better)
8. [License](#license)

[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://vshymanskyy.github.io/StandWithUkraine)

# Installation

Installation for PHP 7.1 and later (PHP 8 as well):

```bash
composer require rawr/t-regx
```

T-Regx only requires `mb-string` extension. No additional dependencies or extensions are required.

## What T-Regx is and isn't

- ### T-Regx is not a tool for building regular expressions

  We don't want to build the patterns for you. T-Regx is to be used exactly with "raw patterns".

  :bulb: For a tool to help you build and understand your patterns, consider using
  [PHPVerbalExpressions](https://github.com/VerbalExpressions/PHPVerbalExpressions):

  ```php
  $regex = new VerbalExpressions();
  $regex->startOfLine()->then("http")->maybe("s")->then("://")->maybe("www.")->endOfLine();
  ```

- ### T-Regx is a regex solution as it should've been made in PHP

  In our humble opinions, T-Regx is a well-crafted, robust, reliable and predictable tool for using regular expression
  in modern applications. It eliminates unknowns and complexity, for the sake of concise code and revealing intentions.
  It utilizes numerous, performant and lightweight checks and operations to ensure each method does exactly what it's
  supposed to.

    - T-Regx uses `preg_match()`/`preg_replace()` as an engine internally, but doesn't leak the horribleness of their
      design out.
    - T-Regx is to `preg_match()`, what PDO was to `mysql_query()`.

    #### Read more, [Scroll to "Overview"](#why-t-regx-stands-out)...

# API

**You** choose the interface:

- I choose to **keep PHP methods** *(but protected from errors/warnings)*:

  [Scroll to see](#no-change-in-api-for-legacy-projects) - `preg::match_all()`, `preg::replace_callback()`, `preg::split()`
- I choose the **modern regex API**:

  [Scroll to see](#written-with-clean-api) - `pattern()->test()`, `pattern()->match()`, `pattern()->replace()`

For legacy projects, we suggest `preg::match_all()`. For standard projects, we suggest `pattern()`.

# Current work in progress

Current development priorities, regarding release of 1.0:

- Separate SafeRegex and CleanRegex into to two packages, so users can choose what they want #103
- Add documentation to each T-Regx public method #17 \[in progress]
- Release 1.0
- Revamp of [t-regx.com](https://t-regx.com/) documentation \[in progress]

# Documentation

Full API documentation is available at [t-regx.com](https://t-regx.com/). List of changes is available
in [ChangeLog.md](https://github.com/T-Regx/T-Regx/blob/develop/ChangeLog.md).

# Try it online, in your browser!

Open [T-Regx fiddle](https://repl.it/github/T-Regx/fiddle) and start playing around right in your browser.

# Why T-Regx stands out?

:bulb: [See documentation at t-regx.com](https://t-regx.com/)

* ### No change in API for legacy projects
    * You can use T-Regx exception-based error handling, without changing your API much. Simply swap `preg_match()` to
      `preg::match()`, and the method will only ever throw exceptions! Won't return `null` or `false` or issue a warning
      or a notice. Nor will it throw a fatal error.
    * Arguments, structure and return types remain the same. Your code will not break.
    * You still deal with complex and unintuitive API of PHP, but we handle the errors and warnings for you.

* ### Prepared patterns

  Using user data (for example with `preg_quote()`) isn't always safe with PCRE, as well as just not being that convenient to use. T-Regx
  provides `Pattern::inject()`, designed specifically for handling potentially unsafe data. `Pattern::mask()` allows converting user-supplied masks into
  full-fledged patterns safely. Use
  `Pattern::template()` for constructing more complex patterns.

* ### Working **with** the developer
    * Errors:
        * Not even touching your error handlers or exception handlers **in any way**!
        * In case of an error PHP either: triggers an error, issues a warning/notice, returns `null`/`false`/`-1`, sets a flag to be read later
          by `preg_last_error()`, sometimes *does nothing*, and sometimes crashes the application by throwing fatal error. T-Regx does none of those, and simply
          throws a dedicated exception.
        * Preventing fatal errors.
        * Different error messages are issued on different PHP versions, in T-Regx they are unified.
    * Strings:
        * [Tracking offset](https://t-regx.com/docs/replace-match-details) and subjects while replacing strings.
        * [Fixing error with multibyte offset (utf-8 safe)](https://t-regx.com/docs/match-details#offsets).
        * Separate methods for positions:
            * `offset()` - which returns position of a match in characters in UTF-8
            * `byteOffset()` - which returns position of a match in bytes, regardless of encoding
    * Groups:
        * PHP provides groups in an `array`. When read, `$match['group']`, regardless if the group name is invalid, the group is missing, the group is unmatched
          or matched an empty string - PHP handles them identically.
        * What the values in the `array` *really mean* is a complex mess.
        * In T-Regx, when invalid group named is used `get('!@#')` - `InvalidArgumentException` is thrown. When attempt
          to read a missing group \- `NonexistentGroupException` is thrown. For a case of valid group, reading a group
          that happens not to be matched - `GroupNotMatchedException` is thrown, or you can use `matched()` method.
    * Simple methods
        * T-Regx exposes functionality by simple methods, which return `int`, `string`, `string[]` or `bool`, and aren't
          nullable. If you wish to do something with your match or pattern, there's probably a method for that, which
          does exactly and only that.

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory (if you use `pattern()`). Methods `preg::match()`/`preg::replace()` still
  require them, not to introduce unnecessary changes in your legacy project.

* ### Converting Warnings/Errors to Exceptions
    * Detects **malformed patterns** in `preg_()` and throws `MalformedPatternException`. This is impossible to catch
      with `preg_last_error()`.
    * Notices, warnings or errors during `preg::` are converted to exceptions, for
      example `CatastrophicBacktrackingException`.
    * In some cases, `preg_()` methods might fail, return `false`/`null` and **NOT** trigger a warning (basically silence it). T-Regx detects those silent fails
      by analyzing return types and throws `SuspectedReturnPregException` in that case.
    * Not every error in PHP can be read from `preg_last_error()`, however T-Regx throws dedicated exceptions for those events.

* ### Written with clean API
    * Descriptive, simple interface
    * SRP methods
    * UTF-8 support out-of-the-box
    * `No Reflection used`, `No (...varargs)`, `No (boolean arguments, true)`, `(No flags, 1)`
      , `[No [nested, [arrays]]]`
    * Inconsistencies between PHP versions are eliminated in T-Regx

* ### Protects you from fatal errors
  Certain arguments cause fatal errors with `preg_()` methods, which terminate the application and can't be caught. T-Regx will predict if given argument would
  cause a fatal error, and will throw a catchable exception instead,

* ### Fixes PHP bugs in regular expressions
  PHP fails for some really simple patterns. For example, with vanilla PHP using patterns ending with `\ `
  (even escaped one!) ends in a parse error. With T-Regx that **just** works: `pattern('\\')`.

* ### T-Regx follows the philosophy of Uncle Bob and "Clean Code"

  Function should do one thing, it should do it well. A function should do exactly what you expect it to do. No surprises.

# What's better

![Ugly api](https://t-regx.com/img/external/readme/preg.png)

or

![Pretty api](https://t-regx.com/img/external/readme/t-regx.png?)

# Sponsors

- [Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)
- [BarxizePL](https://github.com/BarxizePL) - Thanks!

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License

T-Regx is [MIT licensed](LICENSE).
