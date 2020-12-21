T-Regx Changelog
================

Incoming in 0.9.14
------------------

* Breaking changes
    * Rename `DetailGroup.replace()` to `DetailGroup.substitute()`

* Features
    * Add `Detail.textByteLength()` #88
    * Add `DetailGroup.textByteLength()` #88
    * Add `match()->flatMapAssoc()` #88
    * Add `match()->groupBy()->flatMapAssoc()` #88

     Otherwise identical to `flatMap()`, but since `flatMapAssoc()` doesn't use
     [`array_merge()`], the `integer` keys won't be reindexed - returning an integer
     key from a `flatMapAssoc()`. If a given key was already returned previously, the
     later value will be preserved. It's useful for associative arrays with `integer` keys.
     For sequential arrays (or arrays with `string` keys), feel free to use `flatMap()`.
    * Add `match()->groupByCallback()` (previously only `match()->fluent()->groupByCallback()`) #80
    * Add `match()->nth()` (previously only `match()->fluent()->nth()`) #80

Added in 0.9.13
---------------

* Breaking changes
    * None

* Deprecation
    * Deprecate `Match`, use `Detail` instead.
    * Deprecate `ReplaceMatch`, use `ReplaceDetail` instead.
    * Deprecate `MatchGroup`, use `DetailGroup` instead.
    * Deprecate `ReplaceMatchGroup`, use `ReplaceDetailGroup` instead.

      In preparation for PHP 8, in which `match` is a new keyword, we deprecate
      `Match` and `ReplaceMatch`. `Match` will become an invalid class name in PHP 8.

      Classes `Match`, `ReplaceMatch`, `MatchGroup` and `ReplaceMatchGroup` will remain 
      in T-Regx (as deprecated) until T-Regx drops support for PHP 7.

* Features
    * Add `NotReplacedException.getSubject()`
    * Add `DetailGroup.subject()`
    * Add `ReplaceDetailGroup.subject()`
    * Add `pattern()->replace()->focus(group)` #82

      It allows the replacement mechanism to **focus** on a single group,
      so only the focused capturing group will change; the rest of the whole 
      match will be left as it was.

    * Added proper handling of `/J` flag #84

      Previously, duplicate patterns added a form of unpredictability -
      the structure of the group (order, index, name) depended on the group
      appearance in the pattern, which is fine. However, its value (text, offset) 
      depended on which group was matched (that's what we call strategy 2).
      That's the consequence of php storing only one named group in the result, 
      since PHP arrays can't hold duplicate keys.

      That's another gotcha trap set by PHP, and we need a reasonable mechanism
      in T-Regx to handle it.

      Since now, every method (inline groups, group in `Match`, etc.) predictably 
      depends on the order of the group in the pattern (that's what we call strategy 1),
      even the value (text, offset), which previously were kind of random.

    * Added `Match.usingDuplicateName()` method, which allows the user to use the
      less predictable behaviour (which was the default, previously). 

      For safety, groups returned from `usingDuplicateName()` don't have `index()` 
      method, since it allows strategy 2, and strategy 2 indexes of groups are
      sometimes unpredictable. Group returned there extends a different interface,
      not `DetailGroup` as usual, but `DuplicateNamedGroup` - that's an otherwise 
      identical interface, except it doesn't have `index()` method. Of course, 
      regular `group(int|string)` groups still have `index()` method, since they 
      use strategy 1 now.

      * `Match.group('group')` previously would return strategy 2, now returns strategy 1.
      * `Match.usingDuplicateName().group('group')` returns group by strategy 2 (previously default)
      
      There is currently no way to use strategy 2 for inline groups or aggregate group methods,
      only for `Match`/`Detail` details.

* Other
    * Updated some exceptions' messages format; most notably, indexed groups as formatted as `#2`, 
      and named groups as `'group'`.

* SafeRegex
    * After calling [`preg_match()`] with overflowing offset, [`preg_last_error()`] would return
     [`PREG_INTERNAL_ERROR`], which T-Regx would handle, throwing `RuntimePregException` with proper message. 
      Negative offsets would be ignored.

      Since now, T-Regx throws [`\InvalidArgumentException`] in both cases.

Added in 0.9.12
---------------

* Bug fixes
    * Fixed an occasional `TypeError` (Bug introduced in 0.9.11, fixed in 0.9.12)

      Calling `group()->orThrow()` on a non-matched group without argument would cause `TypeError`.

Added in 0.9.11
---------------

* Breaking changes
    * Added `null`-safety to `pattern()->replace()`:
        * Returning `null` from `replace()->callback()` throws `InvalidReturnValueException`.
        * Returning `null` from `replace()->otherwise()` throws `InvalidReturnValueException`.
        * Returning `null` from `replace()->by()->group()->orElse()` throws `InvalidReturnValueException`.
    * Renamed `pattern()->replace()->by()->group()` methods:
        * Renamed `orThrow(string)` to `orElseThrow(string)`.
        * Renamed `orIgnore()` to `orElseIgnore()`.
        * Renamed `orEmpty()` to `orElseEmpty()`.
        * Renamed `orReturn(string)` to `orElseWith(string)`.
        * Renamed `orElse(callable)` to `orElseCalling(callable)`.
    * Renamed and added `pattern()->replace()->by()->group()->map()` methods:
        * Renamed `orThrow(string)` to `orElseThrow(string)`.
        * Added `orElseIgnore()`.
        * Added `orElseEmpty()`.
        * Renamed `orReturn(string)` to `orElseWith(string)`.
        * Renamed `orElse(callable)` to `orElseCalling(callable)`.

* Features
    * Prepared patterns:
        * Restored `Pattern::prepare()`, but without alteration. #78
        * Restored `PatternBuilder::prepare()`, but without alteration. #78
    * Match tail (as `offset()`, but from the end-side):  #83
        * Add `Match.tail()`.
        * Add `Match.byteTail()`.
        * Add `MatchGroup.tail()`.
        * Add `MatchGroup.byteTail()`.
        * Add `ReplaceMatchGroup.tail()`.
        * Add `ReplaceMatchGroup.byteTail()`.
    * Added method `getPregPattern()` to exceptions: #85
        * `PregException`
            * `CompilePregException`
                * `MalformedPatternException`
            * `RuntimePregException`
                * `SubjectEncodingPregException`
                * `Utf8OffsetPregException`
                * `CatastrophicBacktrackingPregException`
                * `RecursionLimitPregException`
                * `JitStackLimitPregException`
            * `InvalidReturnValueException`
* Fixed inconsistencies
    * Duplicated pattern exception message changes offset after PHP 7.3. Since now,
      the messages will be identical on every PHP version.

Added in 0.9.10
---------------
* Breaking changes
    * Renamed `BacktrackLimitPregException` to `CatastrophicBacktrackingPregException`.
    * Removed `Pattern::prepare()`.
    * Removed `PatternBuilder::prepare()`.
    * Renamed `throwingOtherwise()` to `otherwiseThrowing()`.
    * Renamed `returningOtherwise()` to `otherwiseReturning()`.
* Features
    * Add `pattern()->match()->tuple()` method. #76
    * Add `pattern()->match()->triple()` method. #76

Added in 0.9.9
--------------
* Breaking changes
    * Renamed `pattern()->delimiter()` to `pattern()->delimited()`
* Features
    * Add `MatchGroup.equals()`, that allows to compare a potentially unmatched group with a string. 
    * Add `pattern()->match()->group()->filter()` method. #22
    * Add `pattern()->replace()->by()->mapAndCallback()`, which first translates a match by a dictionary (like `map()`),
        and then passes it through callback, before replacing (like `callback()`).
* Enhancements
    * [Prepared patterns] correctly handle whitespace with `x` ([`PCRE_EXTENDED`]) flag. #40
* SafeRegex
    * `preg::quote()` throws [`InvalidArgumentException`] when it's called with a delimiter that's not a single character.
    * Handled PHP Bug [#77827](https://bugs.php.net/bug.php?id=77827), when `\r` was passed at then 
    end of a pattern to [`preg_match()`]/[`preg_match_all()`].
* Bug fixes
    * Fixed a bug in [Prepared patterns] (PCRE mode), when using a malformed pattern caused `TypeError`,
     instead of `MalformedPatternException`. 

Added in 0.9.8
--------------
* Features
    * You can now use `foreach` on `match()`, instead of `forEach()`:
      ```php
      foreach (pattern('\d+')->match('127.0.0.1') as $match) {}
      ```
      and also
      ```php
      foreach (pattern('\d+')->match('127.0.0.1')->asInt() as $digit) {}
      ```
      or
      ```php
      foreach (pattern('\d+')->match('127.0.0.1')->all() as $text) {}
      ```
    * Added `Match.get(string|int)`, which is a shorthand for `Match.group(string|int).text()`.
    * Restored `pattern()->match()->test()`/`fails()` that were removed in version `0.9.2`.

Added in 0.9.7
--------------
* Breaking changes
    * `pattern()->replace()->orElse/Throw/Return->with()` are renamed to
      `otherwise()`/`throwingOtherwise()`/`returningOtherwise()`.
* Features
    * Added `pattern()->match()->asArray()->*` which returns results as an array (as if it was returned by `preg_match()`, but fixed). More below.
* Bug fixes
    * Fixed a bug when `findFirst()` sometimes called `preg_match_all()`, despite previous change.

---

When using `preg_match()` or `preg_match_all()` with `PREG_SET_ORDER`, the last groups that are unmatched or matched an empty string
are removed by PHP! Missing group, unmatched group and group that matched `""` are indistinguishable. Basically, PHP trims any `false`-y group.

T-Regx fixes it by filling the results:
 - `null` always means a group is present, but unmatched
 - `""` means a matched group, that matched an empty string

Added in 0.9.6
--------------
* Breaking changes
    * `pattern()->match()->fluent()->distinct()` will no longer re-index elements (will not remove keys).
      - To re-index keys, use `distinct()->values()`.
      - `pattern()->match()->distinct()` still re-indexes keys.
    * Rename `NoFirstElementFluentException` to `NoSuchElementFluentException`
* Enhancements 🔥
    * Every `match()->...()->first()` method calls `preg_match()`, instead of `preg_match_all()`. More below.
* Features
    * Added `pattern()->match()->fluent()->nth(int)` used to get an element based on an ordinal number.
    * Added `pattern()->match()->asInt()`. More below.

---

#### About `preg_match()` vs `preg_match_all()`:
Previously `preg_match()` was called only by:
- `match()->first()`
- `match()->findFirst()`

Any other `match()` method (e.g. `map()`, `forEach()`, etc.) used [`preg_match_all()`]. From now on, 
where possible, [`preg_match()`] is also used for:
- `fluent()->first()`
- `asInt()->first()` / `asInt()->fluent()->first()`
- `group()->first()`
- `offsets()->first()`
- `group()->offsets()->first()`
- Any method after `fluent()`, for example `fluent()->map()->first()`

The same applies to the methods above ending with `findFirst()`.

The change was made because of two reasons:
- Performance (matching only the first occurrence is faster than all of them)
- There are cases where the 2nd (or 3rd, `n`-th) occurrence would have thrown an error (e.g. catastrophic backtracking).
  Now, such string can be worked with, by calling [`preg_match()`] and returning right after first match.

The only exception to this rule is `filter()->first()`, which still calls [`preg_match_all()`]. 

#### About `asInt()` chain

 - New method `asInt()` can be chained with any `match()` method:
   - `match()->asInt()->all(): int[];`
   - `match()->asInt()->only(int $limit): int[];`
   - `match()->asInt()->first(callable $consumer = null): int;`
   - `match()->asInt()->forEach(callable $consumer): void;`
   - `match()->asInt()->findFirst(callable $consumer): Optional<int>;`
   - `match()->asInt()->count(): int;` though it doesn't change anything
   - `match()->asInt()->iterator(): \Iterator<int>;`
   - `match()->asInt()->map(callable $mapper): int[];`
   - `match()->asInt()->flatMap(callable $mapper);`
   - `match()->asInt()->distinct(): int[];`
   - `match()->asInt()->filter(callable $predicate): int[];`
 - Callbacks passed to `first()`/`map()`/`flatMap()` etc. receive `int`.
 - `asInt()->fluent()` is slightly better than `fluent()->asInt()`:
    - `fluent()->asInt()` creates `Match` details for each occurrence, which are then cast to `int`.
    - `asInt()->fluent()` simply returns matches as `int`.

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
* Enhancements
   * When no automatic delimiter (`/`, `#`, `%`, `~`, etc.) is applicable, character
     `0x01` is used (provided that it's not used anywhere else in the pattern). #71
* Features
   * Added `match()->group()->findFirst()` #22 #70
   * Added alternating groups in prepared patterns 🔥
       - `Pattern::bind()`, `Pattern::inject()` and `Pattern::prepare()` still receive `string` (as an user input),
       but they can also receive `string[]`, which will be treated as a regex *alternation group*:
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
* Bug fixes
   * Previously, we added uniform quoting of `#` character on different PHP versions. Well, sorry to say that, we also
     made a bug doing that, when `#` was also a delimiter. This bug is fixed now.

Added in 0.9.4
--------------

* Breaking changes
   * Renamed `CleanRegexException` to `PatternException`
   * Moved `RegexException` to  `/TRegx` from `/TRegx/CleanRegex/Exception`
   * Simplified namespace of public exceptions:
     - From `TRegx/CleanRegex/Exception/CleanRegex` to `TRegx/CleanRegex/Exception`
* Enhancements
   * Updated the hierarchy of public exceptions:
     - `RegexException`
       - `PregException` (extends `RegexException`, instead of `\Exception`)
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
    * helper `pattern()` method, `Pattern` and `PatternBuilder` now return `PatternInterface`, instead of `Pattern` class.
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

[`preg_match()`]: https://www.php.net/manual/en/function.preg-match.php
[`preg_match_all()`]: https://www.php.net/manual/en/function.preg-match-all.php
[`preg_last_error()`]: https://www.php.net/manual/en/function.preg-last-error.php
[`InvalidArgumentException`]: https://www.php.net/manual/en/class.invalidargumentexception.php
[`\InvalidArgumentException`]: https://www.php.net/manual/en/class.invalidargumentexception.php
[Prepared patterns]: https://t-regx.com/docs/handling-user-input
[`PCRE_EXTENDED`]: https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php
[`PREG_INTERNAL_ERROR`]: https://www.php.net/manual/en/pcre.constants.php
