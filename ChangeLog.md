T-Regx Changelog
================

Incoming in 0.9.6
-----------------

* Features:
    * Added `pattern()->match()->asInt()` which can be then chained with any `match()` method:
       - for `match()->all()`, there's `match()->asInt()->all()` which returns an array of integers
       - for `match()->first()`, there's `match()->asInt()->first()` which returns an integer

      Any `match()` method can be chained after `asInt()`:
        - `all(): array;`
        - `only(int $limit): array;`
        - `first(callable $consumer = null);`
        - `forEach(callable $consumer): void;`
        - `findFirst(callable $consumer): Optional;`
        - `count(): int;`
        - `iterator(): \Iterator;`
        - `map(callable $mapper);`
        - `flatMap(callable $mapper);`
        - `distinct();`
        - `filter(callable $predicate);`

      Callbacks passed to `first()`/`forEach()`/etc. receive `int`.

Added in 0.9.5
--------------

* Breaking changes
    * Removed:
       - `pattern()->match()->fluent()->iterate()` 
       - `pattern()->match()->group()->iterate()` 
       - `pattern()->match()->group()->fluent()->iterate()`

      as `iterate()` was only needed as a substitute for `forEach()`, pre PHP 7, where methods couldn't be named with keywords.
    * Renamed:
       - `pattern()->match()->forFirst()` to `findFirst()` #70
* Enhancements:
   * When every of the automatic delimiters is exhausted (`/`, `#`, `%`, `~`, etc.), character
     `0x01` is used (provided that it's not used anywhere else in the pattern). #71
* Features
   * Added `match()->group()->findFirst()` #22 #70
   * Added alternating groups in prepared patterns 🔥
       - `Pattern::bind()`, `Pattern::inject()` and `Pattern::prepare()` still receive `string` (as an user input),
       but they also receive `string[]`, which will be treated as a regex alternation group:
         ```php
         Pattern::bind('Choice: @values', [
             'values' => ['apple?', 'orange', 'pear']
         ]);
         ```
         is similar to
         ```
         Pattern::of('Choice: (apple\?|orange|pear)')
         ```
         Of course `'apple?'` and other values are protected against user-input malformed patterns.
* Bug fixes:
   * Previously, we added uniform quoting of `#` character on different PHP versions. Well, sorry to say that, we also
     made a bug doing that, when `#` was also a delimiter. This bug is fixed now.

Added in 0.9.4
--------------

* Breaking changes
   * Renamed `CleanRegexExceptions` to `PatternException`
   * Moved `RegexExceptions` to  `/TRegx` from `/TRegx/CleanRegex/Exception`
   * Simplified namespace of public exceptions:
     - From `TRegx/CleanRegex/Exception/CleanRegex` to `TRegx/CleanRegex/Exception`
* Enhancements:
   * Updated the hierarchy of public exceptions:
     - `RegexExceptions`
       - `PregException` (extends `RegexExceptions`, instead of `\Exception`)
       - `PatternException`
         - `IntegerFormatException` (extends `PatternException`, instead of `\Exception`)
         - `NoFirstElementFluentException` (extends `PatternException`, instead of `\Exception`)
   * Previously, `RuntimePregException` was used to indicate every error that was reported by [`preg_last_error()`].
     Now, the following subclasses of `RuntimePregException` are thrown:
     - `SubjectEncodingPregException` for `PREG_BAD_UTF8_ERROR`
     - `Utf8OffsetPregException` for `PREG_BAD_UTF8_OFFSET_ERROR`
     - `BacktrackLimitPregException` for `PREG_BACKTRACK_LIMIT_ERROR`
     - `RecursionLimitPregException` for `PREG_RECURSION_LIMIT_ERROR`
     - `JitStackLimitPregException` for `PREG_JIT_STACKLIMIT_ERROR`
* Features
   * Added `match()->groupBy()`/`match()->filter()->groupBy()`:
     - `match()->groupBy()->texts()`
     - `match()->groupBy()->map(callable<Match>)`
     - `match()->groupBy()->flatMap(callable<Match>)`
     - `match()->groupBy()->offsets()`/`byteOffsets()`

     when `groupBy()` is preceded by `filter()`, it will take indexes, limits, matches order and user data into account.

Added in 0.9.3
--------------
* Breaking changes
    * Renamed exceptions:
      - `SafeRegexException` to `PregException`
      - `CompileSafeRegexException` to `CompilePregException`
      - `RuntimeSafeRegexException` to `RuntimePregException`
      - `SuspectedReturnSafeRegexException` to `SuspectedReturnPregException`
    * Removed `pattern()->match()->iterate()` - it was only needed as a substitute for `forEach()`, pre PHP 7, where
      methods couldn't be named with keywords.
* Features
    * Added `preg::last_error_msg()`, which works like `preg::last_error()`, but returns a human-readable message, 
      instead of `int`.
* Fixing PHP
    * [`preg_match()`] in some cases returns `2`, instead of `1`. T-Regx fixes this bug by always returning `1`, on every
   PHP version (https://bugs.php.net/bug.php?id=78853).

Added in 0.9.2
--------------
* Breaking changes
    * Methods `pattern()`/`Pattern::of()` no longer "magically" guess whether a pattern is delimited or not.
      `Pattern::of()` assumes pattern *is* delimited, new `Pattern::pcre()` takes an old-school delimited pattern.
    * Constructor `new Pattern()` is no longer a part of T-Regx API. Use `Pattern::of()`/`pattern()`
    * Renamed `Match.parseInt()` to `Match.toInt()` (the same for `MatchGroup`)
    * Removed `pattern()->match()->test()`/`fails()`. From now on, use `pattern()->test()`/`fails()`
    * Removed `is()`:
        - `is()->delimited()`
        - `is()->usable()`
        - `is()->valid()` is changed to `valid()`
    * Removed `split()->ex()`, changed `split()->inc()` to `split()`
* Features
    * Added `Match.group().replace()` 🔥
    * Added `pattern()->match()->fluent()` 🔥
    * Added `pattern()->match()->asInt()`
    * Added `pattern()->match()->distinct()` (leaves only unique matches)
    * Added prepared pattern method `Pattern::inject()`/`Pattern::bind()` (see below)
    * In `pattern()->match()->groups()`:
        * Added `groups()->forEach()`/`iterate()`
        * Added `groups()->flatMap()`
        * Added `groups()->map()`
        * Added `group()->fluent()`
        * Added `groups()->names()` (and `namedGroups()->names()`)
        * Added `groups()->count()` (and `namedGroups()->count()`)
    * Added `match()->offsets()->fluent()`
    * Added `match()->group(string)->offsets()->fluent()`
    * Added `pattern()->forArray()->strict()` which throws for invalid values, instead of filtering them out
* SafeRegex
    * Added `preg::grep_keys()` 🔥, that works exactly like `preg::grep()`, but filters by keys (also accepts [`PREG_GREP_INVERT`](https://www.php.net/manual/en/function.preg-grep.php))
* Enhancements/updates
    * Method `by()->group()->orElse()` now receives lazy-loaded `Match`, instead of a subject
    * Added `withReferences()` to `CompositePattern.chainedReplace()`
    * Previously named `Pattern::inject()` is renamed to `Pattern::bind()`
    * The `Pattern::bind()` (old `Pattern::inject()`) still accepts values as an associative array, but new `Pattern::inject()` receives values without regard for the keys.
    * Fixed passing invalid types to `forArray()`. Previously, caused fatal error due to internal `preg_grep()` implementation.
* Other
    * Now `MalformedPatternException` is thrown, instead of `CompileSafeRegexException`, when using invalid PCRE syntax.
    * Returning `Match` from `replace()->callback()` (instead of `Match.text()` as `string`)
    * Match `+12` is no longer considered a valid integer for `isInt()`/`toInt()`
    * Unnamed group will be represented as `null` in `Match.groupNames()`, instead of being simply ignored
    * `helper()` method, `Pattern` and `PatternBuilder` now return interface `PatternInterface`, instead of `Pattern` class.
      `Pattern` class now only holds static utility methods, and `PatternImpl` holds the pattern implementation.
* Maintenance
    * PhpUnit throws different exceptions because of [PHP `__toString()` exception policy change](https://wiki.php.net/rfc/tostring_exceptions).

Foot note:
 - Apart from PHP type hints, every version up to this point could be run on PHP 5.3 (if one removes type hints from 
   code, one can run T-Regx on PHP 5.3). Every error, exception, malfunction, inconsistency was handled correctly by 
   T-Regx. From this version on (`0.9.2`), handling of the errors and inconsistencies is dropped, since T-Regx now 
   only supports PHP 7.1.

Added in 0.9.1
--------------

* Features
    * Added `Match.textLength()`
    * Added `Match.group().textLength()`
    * Added `Match.groupsCount()`
    * Added:
       - `by()->group()->orIgnore()`
       - `by()->group()->orElse()`
       - `by()->group()->callback()` which accepts `MatchGroup` as an argument

Available in 0.9.0
------------------

* Features
    * Pass flags as `pattern()` second argument
    * Add `Match.groups()` and `Match.limit()`
    * Add `Match.group()->all()` 
    * Add `Match.getUserData()`/`setUserData()` 
    * Add `ReplaceMatch.modifiedSubject()`
    * Returning from `match()->first(callable)` modifies its return value
    * Add `pattern()->remove()`
    * Add `pattern()->replace()->by()`
    * Add `match()->only(int)`
    * Add `match()->flatMap()`
    * Add `match()->group()->all()` / `first()` / `->only()`
    * Add `match()->iterator()`
    * Add `match()->forFirst()`
        * with methods `orReturn()`, `orElse()` and `orThrow()`
        * `orThrow()` can instantiate exceptions by class name (with one of predefined constructor signatures)
    * `match->only(i)` calls [`preg_match()`] for `i=1`, and `preg_match_all()` for other values
    * `pattern()->match()` is `\Countable`
    * Add UTF-8 support for methods `offset()`, `modifiedOffset()` and `modifiedSubject()`
    * Add `split()->filter()`
    * Add `NotMatched.groupsCount()`
    * Add `CompositePattern` (#8)
    * Add `PatternBuilder` with `prepare()`, `inject()` and `compose()` methods (#25)
    * Use `PREG_UNMATCHED_AS_NULL` if PHP version is supported
    * Add `Pattern::unquote()`
* Tests
    * Split tests into `\Test\Unit`, `\Test\Integration`, `\Test\Functional` and `\Test\Feature` folders 
    * Add dynamic skip for `ErrorsCleanerTest`
    * Handle [PHP bugfix in 7.1.13](https://bugs.php.net/bug.php?id=74183).
* Other
    * Set `\TRegx` namespace prefix
    * Add `ext-mbstring` requirement to `composer.json`.
    * [`preg_match()`] won't return unmatched groups at the end of list, which makes validating groups and general
      work with group names impossible. Thanks to `GroupPolyfillDecorator`, a second call to `preg_match_all()` is done
      to get a list of all groups (even unmatched ones). The call to `preg_match_all()` is of course only in the case
      of `Match.hasGroup()` or similar method. Regular methods like `Match.text()` won't call `preg_match_all()`
* Debug
    * Add `pregConstant` field to `RuntimeError`. Only reason to do it is so if you **catch the exception it 
    in debugger**, you'll see constant name (ie. `PREG_BAD_UTF8_ERROR`) instead of constant value (ie. `4`).
    * Handle bug [PHP #75355](https://bugs.php.net/bug.php?id=75355)
* Bug fixes
    * `preg::replace()` and `preg::filter()` only consider `[]` error prone if input subject was also an empty array.

[`preg_match()`]:https://www.php.net/manual/en/function.preg-match.php
[`preg_last_error()`]:https://www.php.net/manual/en/function.preg-last-error.php
