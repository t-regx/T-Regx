T-Regx Changelog
================

Incoming
--------

* Breaking changes
    * Removed `replace()->otherwise()`. Use `counting()` instead.
    * Removed `replace()->otherwiseReturning()`. Use `counting()` instead.
    * Removed `replace()->otherwiseThrowing()`. Use `counting()` or `atLeast()` instead.

Added in 0.17.0
---------------

* Bug fixes
    * Fixed a bug when returning non-string value from `orElseCalling()` didn't throw `InvalidReturnValueException`
* Breaking changes
    * Renamed `FluentMatchPattern` to `Stream`, similar to Java 8 streams
    * Renamed `fluent()` to `stream()`, similar to Java 8 streams
    * Renamed `NoSuchElementFluentException` to `NoSuchStreamElementException`
* Features
    * Add `IntStream.stream()`

Added in 0.16.0
---------------

* Breaking changes
    * Removed `FluentMatchPatternException`. In case of `asInt()`, `InvalidIntegerTypeException` is thrown instead.
    * Methods `asInt()` and `offsets()` return `IntStream` instead of `FluentMatchPattern`.
    * Updated the rules when exceptions are thrown from `asInt()`, `offsets()` and `fluent()`:
        * Exceptions thrown from `IntStream`:
            * `pattern()->match()->asInt()` throws `SubjectNotMatchedException`
            * `pattern()->match()->offsets()` throws `SubjectNotMatchedException`
            * `pattern()->match()->group()->asInt()` throws `SubjectNotMatchedException` or `GroupNotMatchedException`
            * `pattern()->match()->group()->offsets()` throws `SubjectNotMatchedException` or `GroupNotMatchedException`
        * Exception thrown from `FluentMatchPattern`:
            * `pattern()->match()->fluent()` throws `NoSuchElementFluentException`
            * `pattern()->match()->asInt()->fluent()` throws `NoSuchElementFluentException`
            * `pattern()->match()->offsets()->fluent()` throws `NoSuchElementFluentException`

      Basically, `MatchPattern` and `IntStream` throw match-related exceptions (`SubjectNotMatchedException`
      or `GroupNotMatchedException`), whereas `FluentMatchPattern` throws fluent-related
      exception: `NoSuchElementFluentException`.
  * Updated exception messages from `asInt()`, `offsets()` and `fluent()`.
  * `MatchPatternInterface` is no longer part of T-Regx public API.

Added in 0.15.0
---------------

* Breaking changes
    * Renamed `Pattern::template()` to `Pattern::builder()`
* Features
    * Added `Pattern::template()`, which works similarly to `Pattern::builder()` but allows only one chain

Added in 0.14.1
---------------

* Bug fixes
    * Fixed a bug when calling `filter()->first()` called predicate for more than the first item.
* Deprecation
    * Deprecated `Optional.orThrow()`. Currently `orThrow()` accepts the exception class name. In the future it will
      accept a real `\Throwable` instance. To preserve current behaviour of `orThrow()`, use `orElse()`.

Added in 0.14.0
---------------

* Breaking changes
    * Renamed `Pattern::compose()->allMatch()` to `testAll()`
    * Renamed `Pattern::compose()->anyMatches()` to `testAny()`
    * Renamed `Pattern::compose()->chainedRemove()` to `prune()`
* Features
    * Added `Pattern::compose()->failAny()`, returning `true` if any of the patterns didn't match the subject
    * Added `Pattern::compose()->failAll()`, returning `true` if all the patterns didn't match the subject

Added in 0.13.8
---------------

* Bug fixes
    * Fixed a bug, where using `Pattern::inject('()(?)')` failed parsing
    * Fixed a bug, where using unicode in groups failed parsing
    * Fixed a bug, where using pattern in unclosed comment group failed parsing
    * Added workaround for PHP inconsistencies ragarding backslash in patterns:
        * PHP reports `\c\` as invalid entity, all T-Regx entry points correctly recognize it as valid
        * PHP reports `\Q\` as invalid entity, all T-Regx entry points correctly recognize it as valid
        * PHP reports `(?#\` as invalid entity, all T-Regx entry points correctly recognize it as valid
        * PHP reports `#\` as invalid entity in `X`tended mode, all T-Regx entry points correctly recognize it as valid
* Features
    * Added `Optional.map()`, which resembles Java 8 optionals.
    * `pattern()->match()->asInt()->findFirst()->orElse()` receive `NotMatched` argument
    * `pattern()->match()->asInt()->findNth()->orElse()` receive `NotMatched` argument
    * `pattern()->match()->offsets()->findFirst()->orElse()` receive `NotMatched` argument
    * `pattern()->match()->offsets()->findNth()->orElse()` receive `NotMatched` argument

Added in 0.13.7
---------------

* Breaking changes
    * `Pattern::inject()` no longer supports alteration. Use `Pattern::template()->alteration()`.
* Features
    * Added `pattern()->match()->forEach()` consumer accepts index as a second argument

Added in 0.13.6
---------------

* Bug fixes
    * Fixed a bug, where using `match()->filter()` didn't throw `InvalidReturnValueException`.
    * Fixed a bug, where using `group()->filter()` didn't throw `InvalidReturnValueException`.
    * Fixed a bug, where `Pattern::template()->mask()` keywords weren't taken into account, when choosing a delimiter
* Features
    * Added `Pattern::template()->pattern()`
* Others
    * Updated `ExplicitDelimiterRequiredException` message for `Pattern::of()`
    * Updated `ExplicitDelimiterRequiredException` message for `Pattern::mask()`
    * Updated `ExplicitDelimiterRequiredException` message for `Pattern::template()`

Added in 0.13.5
---------------

* Breaking changes
    * Refactored `Pattern::pcre()` to `Pattern::pcre()->of()`
    * Refactored `Pattern::builder()->pcre()->inject()` to `Pattern::pcre()->inject()`
    * Refactored `Pattern::builder()->pcre()->template()` to `Pattern::pcre()->template()`
    * Removed `Pattern::builder()`.
    * Moved `ReplaceDetail` to `TRegx\CleanRegex\Replace\Detail` namespace
    * Moved `ReplaceGroup` to `TRegx\CleanRegex\Replace\Detail\Group` namespace
* Features
    * Added `Pattern::alteration()` which allows building `Pattern` with just an alteration group.
        * For example `Pattern::alteration(['foo', 'bar'])` is `/(?:foo|bar)/`
    * Added `Pattern::template()->alteration()`
* Bug fixes
    * Fixed a bug, where passing `false` as an alteration value didn't throw `\InvalidArgumentException`.

Added in 0.13.4
---------------

* Features
    * Every method `toInt()`/`isInt()` receives a `$base` optional argument, which defaults to `10`:
        * `Detail.toInt()`, `Detail.isInt()`,
        * `Group.toInt()`, `Group.isInt()`,
        * `ReplaceDetail.toInt()`, `ReplaceDetail.isInt()`,
        * `pattern()->match()->asInt()`
        * `pattern()->match()->group()->asInt()`
* Other
    * We added continuous integration runs for PHP on 32-bit architecutre, to test 32-bit integers with `toInt()`.

Added in 0.13.3
---------------

* Bug fixes
    * Fixed w bug where using `Detail.usingDuplicateName()` didn't throw `NonexistentGroupException`.

Added in 0.13.2
---------------

* Bug fixes
    * Fixed a bug when using `match()->asInt()->keys()->first()` malformed integers would throw
      not `NumberFormatException`.
    * Fixed a bug when using `fluent()->keys()->keys()` (double `keys()`) then T-Regx exceptions wouldn't have been
      thrown.
* Brekaing changes
    * Previously `remaining()` and `filter()` would leave a resulting array with keys that aren't exactly sequential,
      giving the impression the iterated collection is not a list. Now it's fixed, so the resulting array is indexed.

Added in 0.13.1
---------------

* Bug fixes
    * Fixed a bug when using `match()->fluent()->first()` exposed a false-negative in `hasGroup()` from PHP.
    * Fixed a bug when using `match()->group()->fluent()->first()` exposed a false-negative in `hasGroup()` from PHP.
* Other
    * Internal implementation revamp

Added in 0.13.0
---------------

* Breaking changes
    * `pattern()->forArray()` is now the same as previous `pattern()->forArray()->strict()`.
    * Removed `pattern()->forArray()->strict()`.
    * Removed `Pattern::quote()`. Use `preg::quote()`, which behaves in exactly the same way.
    * Move `Pattern::unquote()` to `preg::unquote()`, which behaves in exactly the same way.
    * Removed `pattern()->remove()->all()`. Use `pattern()->prune()` instead.
    * Removed `pattern()->match()->asArray()`. Use `Detail.groups()` or `Detail.namedGroups()` instead.
* Other
    * Using TDD in T-Regx, it wasn't hard to reach 100% coverage quite easily in T-Regx. In order to make the tests even
      better, we decided that the integration tests won't report any coverage, since it doesn't provide any more
      information now (it's always 100%). That doesn't mean all the cases are tested tough, so we decided to disable the
      coverage reports on the Integration tests. Now, the coverage badge will drop, but that doesn't mean we remove any
      tests. We just mark them as "non-reportable", so that we can use the coverage to actually find more untested
      cases.

Added in 0.12.0
---------------

* Features
    * We added internal regular expression parser, that's used when creating Prepared patterns. Now in-pattern
      structures can be properly recognized, eliminating cases of misuse. Most notablly `[@]`, `\Q@\E`, `\@`, `\c@` and
      others, like comment groups and comments in extended mode.
* Breaking changes
    * Prepared patterns now use internal regular expression parser, to determine what is a placeholder and what isn't:
        * Previously, `[@]` would be injected. Now it's treated as `"@"` character-class.
        * Previously, `\Q@\E` would be injected. Now it's treated as `@` literal.
        * Previously, `\c@` would be injected. Now it's `\c@` control character.
        * Previously, `#@\n` would be injected. Now, if `x` flag is used (globally, or as a subpattern), then it's
          treated as `@` comment.
        * Previously, `(?#@)` would be injected. Now it's treated as `@` comment.
        * Previously, `\@` would be treated as `@` literal. This remains unchanged.
    * Mask placeholders are no longer represented as `&` in templates, use `@`.
    * Refactored `Pattern::template()->builder()`. Use `Pattern::template()` now.
    * Removed `Pattern::bind()`. Use `Pattern::inject()` or `Pattern::template()->literal()`.
    * Removed `Pattern::prepare()`. Use `Pattern::inject()`.
    * Removed `Pattern::pcre()->bind()`.
    * Removed `Pattern::pcre()->prepare()`.
    * Removed `Pattern::template()->bind()`.
* Bug fixes
    * Correct type-error in `ValidPattern.isValid()` on PHP 8.1.

Added in 0.11.0
---------------

* Features
    * Added `Detail.usingDuplicateName().get()` #101
    * Added `Detail.usingDuplicateName().matched()` #101
    * Method `Pattern:template()->literal(string)` now accepts `string` argument, allowing for inserting arbitrary
      strings into the pattern.
    * Added `Pattern::builder()`, which works similarly to how `PatternBuilder::builder()` worked.
    * Added `Pattern::literal()` which creates an instance of a pattern with which matches an arbitrary string exactly,
      even when `x` (`EXTENDED`) flag is used. To add in-pattern structures, like `^` or `$`,
      use `Pattern::template()->literal()`.
    * Added `Pattern::template()->literal()`, which is a shorthand for
      `Pattern::template()->builder()->literal()->build()`.
    * Added `Pattern::template()->mask()`, which is a shorthand for `Pattern::template()->builder()->mask()->build()`.
    * Casting `PatternInterface` to `string` results in a delimited pattern
    * Add `Pcre` version helper
* Breaking changes
    * `match()->getIterator()` no longer preserves the keys of values (like `all()`)
    * `match()->group()->getIterator()` no longer preserves the keys of values (like `all()`)
    * Renamed `Pattern::format()` to `Pattern::mask()`
    * Renamed `Pattern::builder()->format()` to `Pattern::builder()->mask()`
    * Renamed `Pattern::template()->format()` to `Pattern::template()->mask()`
    * Refactored `Pattern::template()->formatting()` to `Pattern::template()->builder()->mask()`
    * Method `literal()` now requires argument `'&'`, to escape `&` in-pattern token
    * Removed `PatternBuilder::builder()`. Use `Pattern::builder()`
    * Removed `PatternBuilder::compose()`. Use `Pattern::compose()`
    * Renamed `FormatMalformedPatternException` to `MaskMalformedPatternException`
    * Removed interface `PatternInterface`. Now class `Pattern` is both an instance of a pattern, as well as a
      static-factory, i.e. `Pattern::of()`/`Pattern::inject()`.
* Bug fixes
    * `Pattern::template()` quoted values incorrectly, when delimiter other than `/` or `%` was chosen.

Added in 0.10.2
---------------

* Breaking changes
    * Rename `DetailGroup` to `Group`
    * Rename `ReplaceDetailGroup` to `ReplaceGroup`
    * Rename `BaseDetailGroup` to `CapturingGroup`
* Features
    * Calling `pattern()->replace()` without `all()`/`first()`/`only()`, implicitly assumes `all()`
* Bug fixes
    * Group name `"group\n"` used to be considered valid, now it's correctly being treated as invalid.
* Other
    * [`ReplaceMatch`] is now a class, not an interface.
    * When invalid strings, error messages will now also print invisible characters, for example `"Foo\n"`, instead of
      ```
      "Foo      
      "
      ```
    * Update messages and exceptions thrown in edge-cases from `group()->fluent()` #93

Added in 0.10.1
---------------

* Breaking changes
    * Chainable `pattern()->match()->filter()` is renamed to `remaining()`.

      `pattern()->match()->fluent()->filter()` is not being renamed.
    * After filtering `MatchPattern` with `remaining()`, consecutive `Detail.index()` will no longer be reindexed, they
      will preserve the `index()` they had had before `remaining()`.
    * `match()->fluent()->filter()` no longer reindexes values. To reindex, use `values()`.
* Bug fixes
    * Fixed a bug where `fluent()->flatMap()->first()` would return the `array`, instead of the first element
* Features
    * Add `pattern()->match()->filter()` which returns only matches allowed by the predicate.
    * Add `pattern()->match()->group()->asInt()`
* Other
    * `pattern()->match()->fluent()->filter()->first()` first calls [`preg_match()`], and if that result doesn't match
      the predicate, then it calls [`preg_match_all()`].

Added in 0.10.0
---------------

* Breaking changes
    * Previously deprecated [`Match`] and [`ReplaceMatch`] are now being removed, because of PHP8 keyword [`match`][8].

      Use [`Detail`] and [`ReplaceDetail`] instead.
* Other
    * T-Regx version 0.10 supports PHP 8.

Added in 0.9.14
---------------

* Breaking changes
    * Rename `DetailGroup.replace()` to `DetailGroup.substitute()`
    * Rename `match().groupBy().texts()` to `match().groupBy().all()`
    * [`ReplaceDetail.modifiedOffset()`][2] returned values as bytes, now returns them as characters
    * [`ReplaceDetailGroup.modifiedOffset()`][2] returned values as bytes, now returns them as characters
    * Move `MalformedPatternException` to namespace `\TRegx\Exception`
    * Move `RegexException` to namespace `\TRegx\Exception`
    * `MalformedPatternException` was a class extending `CompilePregException`. Now, `MalformedPatternException`
      extends only `RegexException`. New class, `PregMalformedPatternException` is being thrown everywhere
      `MalformedPatternException` used to be thrown. Don't refactor your `catch (MalformedPatternException $e)`, since
      that's still the recommended handling.
      (but say [`get_class()`] would return `PregMalformedPatternException`). Complete exception structure is described
      in "Exceptions".
    * Rename exceptions
        * Rename `Utf8OffsetPregException` to `UnicodeOffsetException`
        * Rename `SubjectEncodingPregException` to `SubjectEncodingException`
        * Rename `CatastrophicBacktrackingPregException` to `CatastrophicBacktrackingException`
        * Rename `RecursionLimitPregException` to `RecursionException`
        * Rename `JitStackLimitPregException` to `JitStackLimitException`
* Bug fixes
    * Fix a security bug in [`Pattern::bind()`]
    * Using pattern with a trailing backslash (e.g. `"(hello)\\"`) would throw
      `MalformedPatternException` with a really weird message, exposing the implementation details. Now the message
      is `Pattern may not end with a trailing backslash`.
    * Adapt `focus()->withReferences()` so it works exactly as [`preg_replace()`].

      Previously, using a nonexistent or unmatched group with `focus()->withReferences()`
      would throw an exception. But of course, [`preg_replace()`] references `$1` and `\1`
      simply are ignored by PCRE, being replaced by an empty string. So, as of this version both [`withReferences()`]
      and `focus()->withReferences()` ignore the unmatched or nonexistent group as well.
    * Fix an error where optionals didn't work properly for `match()->offsets()->fluent()`
    * Fix an error where `ReplaceDetail` would return malformed `modifiedSubject()` for utf-8 replacements

* Features
    * Add [`ReplaceDetail.byteModifiedOffset()`][2] which returns values as bytes
    * Add [`ReplaceDetailGroup.byteModifiedOffset()`][2] which returns values as bytes
    * Add [`ReplaceDetailGroup.modifiedSubject()`][2]
    * Add pattern formats and pattern templates, a new way of creating pseudo-patterns for user supplied data:
        * Add `Pattern::format()` #79
        * Add `Pattern::template()` #79
    * Add `Detail.textByteLength()` #88
    * Add `DetailGroup.textByteLength()` #88
    * Add `match()->flatMapAssoc()` #88
    * Add `match()->group()->flatMapAssoc()` #88
    * Add `match()->fluent()->flatMapAssoc()` #88
    * Add `match()->groupBy()->flatMapAssoc()` #88

      Otherwise identical to [`flatMap()`], but since `flatMapAssoc()` doesn't use
      [`array_merge()`], the `int` keys won't be reindexed - returning an integer key from a
      `flatMapAssoc()`. If a given key was already returned previously, the later value will be preserved. It's useful
      for associative arrays with `int` keys. For sequential arrays (or arrays with `string` keys), feel free to
      use [`flatMap()`].

    * Add `match()->groupByCallback()` (previously only `match()->fluent()->groupByCallback()` and
      `match()->groupBy()`) #80
    * Add `match()->nth()` (previously only `match()->fluent()->nth()`) #80
    * Add `replace()->counting()`, invoking a callback with the number of replacements performed #90
    * Add `replace()->exactly()`, validating that exactly one/only replacements were performed #90
    * Add `replace()->atLeast()`, validating that at least one/only replacements were performed #90
    * Add `replace()->atMost()`, validating that at most one/only replacements were performed #90
    * Add `pattern()->prune()` which removes every occurrence of a pattern from subject (identical to `remove()->all()`)
* Other:
    * Replace any usage of `\d` to `[0-9]` in the library, since it depends on PHP locale.
    * Added interface `PatternStructureException` which can be used to catch exceptions for errors solely in pattern
      structure (recursion, backtracking, jit limit).

Added in 0.9.13
---------------

* Breaking changes
    * None

* Deprecation
    * Deprecate [`Match`], use [`Detail`] instead.
    * Deprecate [`ReplaceMatch`], use [`ReplaceDetail`] instead.
    * Deprecate [`MatchGroup`], use [`DetailGroup`] instead.
    * Deprecate `ReplaceMatchGroup`, use `ReplaceDetailGroup` instead.

      In preparation for PHP 8, in which [`match`][8] is a new keyword, we deprecate [`Match`] and [`ReplaceMatch`]
      . [`Match`] will become an invalid class name in PHP 8.

      Classes [`Match`], [`ReplaceMatch`], [`MatchGroup`] and `ReplaceMatchGroup` will remain in T-Regx (as deprecated)
      as long as T-Regx doesn't support PHP 8.

* Features
    * Add `NotReplacedException.getSubject()`
    * Add `DetailGroup.subject()`
    * Add `ReplaceDetailGroup.subject()`
    * Add `pattern()->replace()->focus(group)` #82

      It allows the replacement mechanism to **focus** on a single group, so only the focused capturing group will
      change; the rest of the whole match will be left as it was.

    * Added proper handling of `/J` flag #84

      Previously, duplicate patterns added a form of unpredictability - the structure of the group (order, index, name)
      depended on the group appearance in the pattern, which is fine. However, its value (text, offset)
      depended on which group was matched (that's what we call strategy 2). That's the consequence of php storing only
      one named group in the result, since PHP arrays can't hold duplicate keys.

      That's another gotcha trap set by PHP, and we need a reasonable mechanism in T-Regx to handle it.

      Since now, every method (inline groups, group in [`Match`], etc.) predictably depends on the order of the group in
      the pattern (that's what we call strategy 1), even the value (text, offset), which previously were kind of random.

    * Added [`Match.usingDuplicateName()`] method, which allows the user to use the less predictable behaviour (which
      was the default, previously).

      For safety, groups returned from [`usingDuplicateName()`] don't have `index()` method, since it allows strategy 2,
      and strategy 2 indexes of groups are sometimes unpredictable. Group returned there extends a different interface,
      not [`DetailGroup`] as usual, but [`DuplicateNamedGroup`] - that's an otherwise identical interface, except it
      doesn't have `index()` method. Of course, regular `group(int|string)`
      groups still have `index()` method, since they use strategy 1 now.

        * `Match.group('group')` previously would return strategy 2, now returns strategy 1.
        * [`Match.usingDuplicateName().group('group')`] returns group by strategy 2 (previously default)

      There is currently no way to use strategy 2 for inline groups or aggregate group methods, only for [`Match`]
      /[`Detail`] details.

* Other
    * Updated some exceptions' messages format; most notably, indexed groups as formatted as `#2`, and named groups
      as `'group'`.

* SafeRegex
    * After calling [`preg_match()`] with overflowing offset, [`preg_last_error()`] would return
      [`PREG_INTERNAL_ERROR`], which T-Regx would handle, throwing `RuntimePregException` with proper message. Negative
      offsets would be ignored.

      Since now, T-Regx throws [`\InvalidArgumentException`] in both cases.

Added in 0.9.12
---------------

* Bug fixes
    * Fixed an occasional [`TypeError`] (Bug introduced in 0.9.11, fixed in 0.9.12)

      Calling `group()->orThrow()` on a non-matched group without argument would cause [`TypeError`].

Added in 0.9.11
---------------

* Breaking changes
    * Added `null`-safety to [`pattern()->replace()`]:
        * Returning `null` from [`replace()->callback()`] throws `InvalidReturnValueException`.
        * Returning `null` from `replace()->otherwise()` throws `InvalidReturnValueException`.
        * Returning `null` from `replace()->by()->group()->orElse()` throws `InvalidReturnValueException`.
    * Renamed `pattern()->replace()->by()->group()` methods:
        * Renamed [`orThrow(string)`] to [`orElseThrow(string)`].
        * Renamed [`orIgnore()`] to [`orElseIgnore()`].
        * Renamed [`orEmpty()`] to [`orElseEmpty()`].
        * Renamed [`orReturn(string)`] to [`orElseWith(string)`].
        * Renamed [`orElse(callable)`] to [`orElseCalling(callable)`].
    * Renamed and added `pattern()->replace()->by()->group()->map()` methods:
        * Renamed [`orReturn(string)`][1] to [`orElseWith(string)`][1].
        * Renamed [`orElse(callable)`][1] to [`orElseCalling(callable)`][1].
        * Renamed [`orThrow(string)`][1] to [`orElseThrow(string)`][1].
        * Added [`orElseIgnore()`][1].
        * Added [`orElseEmpty()`][1].

* Features
    * Prepared patterns:
        * Restored [`Pattern::prepare()`], but without alteration. #78
        * Restored [`PatternBuilder::prepare()`], but without alteration. #78
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
    * Duplicated pattern exception message changes offset after PHP 7.3. Since now, the messages will be identical on
      every PHP version.

Added in 0.9.10
---------------

* Breaking changes
    * Renamed `BacktrackLimitPregException` to `CatastrophicBacktrackingPregException`.
    * Removed [`Pattern::prepare()`].
    * Removed [`PatternBuilder::prepare()`].
    * Renamed `throwingOtherwise()` to `otherwiseThrowing()`.
    * Renamed `returningOtherwise()` to `otherwiseReturning()`.
* Features
    * Add `pattern()->match()->tuple()` method. #76
    * Add `pattern()->match()->triple()` method. #76

Added in 0.9.9
--------------

* Breaking changes
    * Renamed [`pattern()->delimiter()`] to [`pattern()->delimited()`]
* Features
    * Add `MatchGroup.equals()`, that allows to compare a potentially unmatched group with a string.
    * Add `pattern()->match()->group()->filter()` method. #22
    * Add `pattern()->replace()->by()->mapAndCallback()`, which first translates a match by a dictionary
      (like [`by()->map()`]), and then passes it through callback, before replacing (like [`callback()`]).
* Enhancements
    * [Prepared patterns] correctly handle whitespace with [`PCRE_EXTENDED`] mode. #40
* SafeRegex
    * `preg::quote()` throws [`InvalidArgumentException`] when it's called with a delimiter that's not a single
      character.
    * Handled PHP Bug [#77827](https://bugs.php.net/bug.php?id=77827), when `\r` was passed at then end of a pattern
      to [`preg_match()`]/[`preg_match_all()`].
* Bug fixes
    * Fixed a bug in [Prepared patterns] (PCRE mode), when using a malformed pattern caused [`TypeError`], instead
      of `MalformedPatternException`.

Added in 0.9.8
--------------

* Features
    * You can now use [`foreach`](https://www.php.net/manual/en/control-structures.foreach.php) on [`match()`], instead
      of [`forEach()`]:
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
    * Added [`Match.get(string|int)`], which is a shorthand for `Match.group(string|int).text()`.
    * Restored `pattern()->match()->test()`/[`fails()`] that were removed in version 0.9.2.

Added in 0.9.7
--------------

* Breaking changes
    * `pattern()->replace()->orElse/Throw/Return->with()` are renamed to
      `otherwise()`/`throwingOtherwise()`/`returningOtherwise()`.
* Features
    * Added `pattern()->match()->asArray()->*` which returns results as an array (as if it was returned
      by [`preg_match()`], but fixed). More below.
* Bug fixes
    * Fixed a bug when [`findFirst()`] sometimes called [`preg_match_all()`], despite previous change.

---

When using [`preg_match()`] or [`preg_match_all()`] with [`PREG_SET_ORDER`], the last groups that are unmatched or
matched an empty string are removed by PHP! Missing group, unmatched group and group that matched `""` are
indistinguishable. Basically, PHP trims any `false`-y group.

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
    * Every `match()->...()->first()` method calls [`preg_match()`], instead of [`preg_match_all()`]. More below.
* Features
    * Added `pattern()->match()->fluent()->nth(int)` used to get an element based on an ordinal number.
    * Added `pattern()->match()->asInt()`. More below.

---

#### About `preg_match()` vs `preg_match_all()`:

Previously [`preg_match()`] was called only by:

- [`match()->first()`]
- [`match()->findFirst()`]

Any other [`match()`] method (e.g. [`map()`], [`forEach()`], etc.) used [`preg_match_all()`]. From now on, where
possible, [`preg_match()`] is also used for:

- `fluent()->first()`
- `asInt()->first()` / `asInt()->fluent()->first()`
- `group()->first()`
- `offsets()->first()`
- `group()->offsets()->first()`
- Any method after `fluent()`, for example `fluent()->map()->first()`

The same applies to the methods above ending with [`findFirst()`].

The change was made because of two reasons:

- Performance (matching only the first occurrence is faster than all of them)
- There are cases where the 2nd (or 3rd, n-th) occurrence would have thrown an error (e.g. catastrophic backtracking).
  Now, such string can be worked with, by calling [`preg_match()`] and returning right after first match.

The only exception to this rule is `filter()->first()`, which still calls [`preg_match_all()`].

#### About `asInt()` chain

- New method `asInt()` can be chained with any [`match()`] method:
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
- Callbacks passed to [`first()`]/[`map()`]/[`flatMap()`] etc. receive `int`.
- `asInt()->fluent()` is slightly better than `fluent()->asInt()`:
    - `fluent()->asInt()` creates [`Match`] details for each occurrence, which are then cast to `int`.
    - `asInt()->fluent()` simply returns matches as `int`.

Added in 0.9.5
--------------

* Breaking changes
    * Removed:
        - `pattern()->match()->fluent()->iterate()`
        - `pattern()->match()->group()->iterate()`
        - `pattern()->match()->group()->fluent()->iterate()`

      as [`iterate()`] was only needed as a substitute for [`forEach()`], pre PHP 7, where methods couldn't be named
      with keywords.
    * Renamed:
        - [`match()->forFirst()`] to [`findFirst()`] #70
* Enhancements
    * When no automatic delimiter (`/`, `#`, `%`, `~`, etc.) is applicable, character
      `0x01` is used (provided that it's not used anywhere else in the pattern). #71
* Features
    * Added `match()->group()->findFirst()` #22 #70
    * Added alternating groups in prepared patterns 🔥
        - [`Pattern::bind()`], [`Pattern::inject()`] and [`Pattern::prepare()`] still receive `string` (as a user input)
          , but they can also receive `string[]`, which will be treated as a regex *alternation group*:
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
    * Moved `RegexException` to  `\TRegx` from `\TRegx\CleanRegex\Exception`
    * Simplified the namespace of public exceptions:
        - From `\TRegx\CleanRegex\Exception\CleanRegex` to `\TRegx\CleanRegex\Exception`
* Enhancements
    * Updated the hierarchy of public exceptions:
        - `RegexException`
            - `PregException` (extends `RegexException`, instead of [`\Exception`])
            - `PatternException`
                - `IntegerFormatException` (extends `PatternException`, instead of [`\Exception`])
                - `NoFirstElementFluentException` (extends `PatternException`, instead of [`\Exception`])
    * Previously, `RuntimePregException` was used to indicate every error that was reported by [`preg_last_error()`].
      Now, the following subclasses of `RuntimePregException` are thrown:
        - `SubjectEncodingPregException` for [`PREG_BAD_UTF8_ERROR`]
        - `Utf8OffsetPregException` for [`PREG_BAD_UTF8_OFFSET_ERROR`]
        - `BacktrackLimitPregException` for [`PREG_BACKTRACK_LIMIT_ERROR`]
        - `RecursionLimitPregException` for [`PREG_RECURSION_LIMIT_ERROR`]
        - `JitStackLimitPregException` for [`PREG_JIT_STACKLIMIT_ERROR`]
* Features
    * Added `match()->groupBy()`/`match()->filter()->groupBy()`:
        - `match()->groupBy()->texts()`
        - `match()->groupBy()->map(callable<Match>)`
        - `match()->groupBy()->flatMap(callable<Match>)`
        - `match()->groupBy()->offsets()`/`byteOffsets()`

      when `groupBy()` is preceded by `filter()`, it will take indexes, limits, matches order and user data into
      account.

Added in 0.9.3
--------------

* Breaking changes
    * Renamed exceptions:
        - `SafeRegexException` to `PregException`
        - `CompileSafeRegexException` to `CompilePregException`
        - `RuntimeSafeRegexException` to `RuntimePregException`
        - `SuspectedReturnSafeRegexException` to `SuspectedReturnPregException`
    * Removed [`pattern()->match()->iterate()`] - it was only needed as a substitute for [`forEach()`], pre PHP 7, where
      methods couldn't be named with keywords.
* Features
    * Added [`preg::last_error_msg()`], which works like `preg::last_error()`, but returns a human-readable message,
      instead of `int`.
* Fixing PHP
    * [`preg_match()`] in some cases returns `2`, instead of `1`. T-Regx fixes this bug by always returning `1`, on
      every PHP version (https://bugs.php.net/bug.php?id=78853).

Added in 0.9.2
--------------

* Breaking changes
    * Methods [`pattern()`]/[`Pattern::of()`] no longer "magically" guess whether a pattern is delimited or not.
      [`Pattern::of()`] assumes pattern *is* delimited, new [`Pattern::pcre()`] takes an old-school delimited pattern.
    * Constructor `new Pattern()` is no longer a part of T-Regx API. Use [`Pattern::of()`]/[`pattern()`]
    * Renamed [`Match.parseInt()`] to [`Match.toInt()`] (the same for [`MatchGroup`])
    * Removed [`pattern()->match()->test()`]/[`fails()`]. From now on, use [`pattern()->test()`]/[`fails()`]
    * Removed `is()`:
        - `is()->delimited()`
        - `is()->usable()`
        - `is()->valid()` is changed to [`valid()`]
    * Removed [`split()->ex()`], changed [`split()->inc()`] to [`split()`]
* Features
    * Added `Match.group().replace()` 🔥
    * Added `pattern()->match()->fluent()` 🔥
    * Added `pattern()->match()->asInt()`
    * Added `pattern()->match()->distinct()` (leaves only unique matches)
    * Added prepared pattern method [`Pattern::inject()`]/[`Pattern::bind()`] (see below)
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
    * Added `pattern()->replace()->counting()`
* SafeRegex
    * Added `preg::grep_keys()` 🔥, that works exactly like `preg::grep()`, but filters by keys (also
      accepts [`PREG_GREP_INVERT`](https://www.php.net/manual/en/function.preg-grep.php))
* Enhancements/updates
    * Method [`by()->group()->orElse()`] now receives lazy-loaded [`Match`], instead of a subject
    * Added [`withReferences()`] to `CompositePattern.chainedReplace()`
    * Previously named [`Pattern::inject()`] is renamed to [`Pattern::bind()`]
    * The [`Pattern::bind()`] (old [`Pattern::inject()`]) still accepts values as an associative array, but
      new [`Pattern::inject()`] receives values without regard for the keys.
    * Fixed passing invalid types to [`forArray()`]. Previously, caused fatal error due to internal [`preg_grep()`]
      implementation.
* Other
    * Now `MalformedPatternException` is thrown, instead of `CompileSafeRegexException`, when using invalid PCRE syntax.
    * Returning [`Match`] from [`replace()->callback()`] (instead of [`Match.text()`] as `string`)
    * Match `+12` is no longer considered a valid integer for [`isInt()`]/[`toInt()`]
    * Unnamed group will be represented as `null` in `Match.groupNames()`, instead of being simply ignored
    * helper [`pattern()`] method, [`Pattern`] and [`PatternBuilder`] now return `PatternInterface`, instead
      of [`Pattern`]
      class.
      [`Pattern`] class now only holds static utility methods, and `PatternImpl` holds the pattern implementation.
* Maintenance
    * PhpUnit throws different exceptions because
      of [PHP `__toString()` exception policy change](https://wiki.php.net/rfc/tostring_exceptions).

Footnote:

- Apart from PHP type hints, every version up to this point could be run on PHP 5.3 (if one removes type hints from
  code, one can run T-Regx on PHP 5.3). Every error, exception, malfunction, inconsistency was handled correctly by
  T-Regx. From this version on (0.9.2), handling of the errors and inconsistencies is dropped, since T-Regx now only
  supports PHP 7.1.

Added in 0.9.1
--------------

* Features
    * Added `Match.textLength()`
    * Added `Match.group().textLength()`
    * Added `Match.groupsCount()`
    * Added:
        - [`by()->group()->orIgnore()`]
        - [`by()->group()->orElse()`]
        - `by()->group()->callback()` which accepts [`MatchGroup`] as an argument

Available in 0.9.0
------------------

* Features
    * Pass flags as [`pattern()`] second argument
    * Add `Match.groups()`
    * Add [`Match.limit()`](https://t-regx.com/docs/match-details#limit)
    * Add `Match.group()->all()`
    *
  Add [`Match.getUserData()`](https://t-regx.com/docs/match-details#user-data)/[`setUserData()`](https://t-regx.com/docs/match-details#user-data)
    * Add [`ReplaceMatch.modifiedSubject()`](https://t-regx.com/docs/replace-match-details#modifiedsubject-example)
    * Returning from [`match()->first(callable)`] modifies its return value
    * Add [`pattern()->remove()`](https://t-regx.com/docs/replace-with#remove-occurrence)
    * Add [`pattern()->replace()->by()`](https://t-regx.com/docs/replace-by-group)
    * Add [`match()->only(int)`](https://t-regx.com/docs/match#retrieve-multiple-matches)
    * Add [`match()->flatMap()`]
    * Add `match()->group()->all()`/`first()`/`only()`
    * Add [`match()->iterator()`](https://t-regx.com/docs/match-iterator)
    * Add [`match()->forFirst()`]
        * with methods [`orReturn()`](https://t-regx.com/docs/match-find-first#orreturn),
          [`orElse()`](https://t-regx.com/docs/match-find-first#orelse) and
          [`orThrow()`](https://t-regx.com/docs/match-find-first#orthrow)
        * [`orThrow()`](https://t-regx.com/docs/match-find-first#custom-exceptions-for-orthrow) can instantiate
          exceptions by class name (with one of predefined constructor signatures)
    * [`match->only(i)`](https://t-regx.com/docs/match#retrieve-multiple-matches) calls [`preg_match()`] for `i=1`,
      and [`preg_match_all()`] for other values
    * [`pattern()->match()`](https://t-regx.com/docs/match) is [`\Countable`]
        * Add UTF-8 support for methods `offset()`, `modifiedOffset()` and `modifiedSubject()`
        * Add [`split()->filter()`]
        * Add `NotMatched.groupsCount()`
        * Add [`CompositePattern`] (#8)
        * Add [`PatternBuilder`] with [`prepare()`], [`inject()`] and `compose()` methods (#25)
    * Use [`PREG_UNMATCHED_AS_NULL`] if PHP version is supported
        * Add [`Pattern::unquote()`]
* Tests
    * Split tests into `\Test\Unit`, `\Test\Integration`, `\Test\Functional` and `\Test\Feature` folders
    * Add dynamic skip for `ErrorsCleanerTest`
    * Handle [PHP bugfix in 7.1.13](https://bugs.php.net/bug.php?id=74183).
* Other
    * Set `\TRegx` namespace prefix
    * Add [`ext-mbstring`](https://www.php.net/manual/en/mbstring.installation.php) requirement to
      [`composer.json`](https://getcomposer.org/doc/04-schema.md).
    * [`preg_match()`] won't return unmatched groups at the end of list, which makes validating groups and general work
      with group names impossible. Then, a second call to [`preg_match_all()`] is done to get a list of all groups (even
      unmatched ones). The call to [`preg_match_all()`] is of course only in the case of [`hasGroup()`] or similar
      method. Regular methods like [`Match.text()`] won't call [`preg_match_all()`]
* Debug
    * Add `pregConstant` field to `RuntimeError`. Only reason to do it is so if you **catch the exception it in
      debugger**, you'll see the constant name (i.e. [`PREG_BAD_UTF8_ERROR`]) instead of the constant value (i.e. `4`).
    * Handle bug [PHP #75355](https://bugs.php.net/bug.php?id=75355)
* Bug fixes
    * `preg::replace()` and `preg::filter()` only consider `[]` error prone if input subject was also an empty array.

[`pattern()`]: https://t-regx.com/docs/introduction-clean#entry-points

[`Pattern`]: https://t-regx.com/docs/introduction-clean#entry-points

[`Pattern::of()`]: https://t-regx.com/docs/introduction-clean#entry-points

[`preg_match()`]: https://www.php.net/manual/en/function.preg-match.php

[`preg_match_all()`]: https://www.php.net/manual/en/function.preg-match-all.php

[`preg_replace()`]: https://www.php.net/manual/en/function.preg-replace.php

[`preg_last_error()`]: https://www.php.net/manual/en/function.preg-last-error.php

[`preg_grep()`]: https://www.php.net/manual/en/function.preg-grep.php

[`array_merge()`]: https://www.php.net/manual/en/function.array-merge.php

[`InvalidArgumentException`]: https://www.php.net/manual/en/class.invalidargumentexception.php

[`\InvalidArgumentException`]: https://www.php.net/manual/en/class.invalidargumentexception.php

[Prepared patterns]: https://t-regx.com/docs/handling-user-input

[`PCRE_EXTENDED`]: https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php

[`PREG_INTERNAL_ERROR`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_BAD_UTF8_ERROR`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_BAD_UTF8_OFFSET_ERROR`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_BACKTRACK_LIMIT_ERROR`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_RECURSION_LIMIT_ERROR`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_JIT_STACKLIMIT_ERROR`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_UNMATCHED_AS_NULL`]: https://www.php.net/manual/en/pcre.constants.php

[`PREG_SET_ORDER`]: https://www.php.net/manual/en/pcre.constants.php

[`Match`]: https://t-regx.com/docs/match-details

[`Detail`]: https://t-regx.com/docs/match-details

[`ReplaceMatch`]: https://t-regx.com/docs/replace-match-details

[`ReplaceDetail`]: https://t-regx.com/docs/replace-match-details

[`pattern()->delimiter()`]: https://t-regx.com/docs/delimiters

[`pattern()->delimited()`]: https://t-regx.com/docs/delimiters

[`TypeError`]: https://www.php.net/manual/en/class.typeerror.php

[`withReferences()`]: https://t-regx.com/docs/replace-with#php-style-intentional-references

[`Pattern::pcre()`]: https://t-regx.com/docs/introduction-clean#old-school-patterns

[`Pattern::prepare()`]: https://t-regx.com/docs/prepared-patterns#with-patternprepare

[`Pattern::inject()`]: https://t-regx.com/docs/prepared-patterns#with-patterninject

[`prepare()`]: https://t-regx.com/docs/prepared-patterns#with-patternprepare

[`inject()`]: https://t-regx.com/docs/prepared-patterns#with-patterninject

[`Pattern::bind()`]: https://t-regx.com/docs/prepared-patterns#with-patternbind

[`PatternBuilder::prepare()`]: https://t-regx.com/docs/prepared-patterns#pcre-styled-patterns

[`PatternBuilder`]: https://t-regx.com/docs/prepared-patterns#pcre-styled-patterns

[`preg::last_error_msg()`]: https://t-regx.com/docs/utils#preglast_error_msg

[`pattern()->match()->iterate()`]: https://t-regx.com/docs/match-for-each

[`iterate()`]: https://t-regx.com/docs/match-for-each

[`forEach()`]: https://t-regx.com/docs/match-for-each

[`valid()`]: https://t-regx.com/docs/valid

[`Pattern::unquote()`]: https://t-regx.com/docs/utils#patternunquotes

[`hasGroup()`]: https://t-regx.com/docs/match-groups#group-details

[`Match.get(string|int)`]: http://t-regx.com/docs/match-details#matched-text

[`\Countable`]: https://www.php.net/manual/en/class.countable.php

[`forArray()`]: https://t-regx.com/docs/filter

[`\Exception`]: https://www.php.net/manual/en/class.exception.php

[`match()`]: https://t-regx.com/docs/match

[`first()`]: https://t-regx.com/docs/match-first

[`match()->first(callable)`]: https://t-regx.com/docs/match-first

[`match()->first()`]: https://t-regx.com/docs/match-first#use-first-with-callback

[`findFirst()`]: https://t-regx.com/docs/match-find-first

[`match()->findFirst()`]: https://t-regx.com/docs/match-find-first

[`match()->forFirst()`]: https://t-regx.com/docs/match-find-first

[`map()`]: https://t-regx.com/docs/match-map

[`flatMap()`]: https://t-regx.com/docs/match-map#flatmap

[`match()->flatMap()`]: https://t-regx.com/docs/match-map#flatmap

[`pattern()->replace()`]:  https://t-regx.com/docs/replace

[`replace()->callback()`]: https://t-regx.com/docs/replace-callback

[`callback()`]: https://t-regx.com/docs/replace-callback

[`by()->map()`]: https://t-regx.com/docs/replace-by-map

[`split()`]: https://t-regx.com/docs/split

[`split()->ex()`]: https://t-regx.com/docs/split

[`split()->inc()`]: https://t-regx.com/docs/split

[`split()->filter()`]: https://t-regx.com/docs/split

[`CompositePattern`]: https://t-regx.com/docs/composite-pattern

[`Match.usingDuplicateName()`]: https://t-regx.com/docs/match-groups-j-modifier

[`usingDuplicateName()`]: https://t-regx.com/docs/match-groups-j-modifier

[`Match.usingDuplicateName().group('group')`]: https://t-regx.com/docs/match-groups-j-modifier

[`DuplicateNamedGroup`]: https://t-regx.com/docs/match-groups-j-modifier

[`MatchGroup`]: https://t-regx.com/docs/match-group

[`DetailGroup`]: https://t-regx.com/docs/match-group

[`pattern()->match()->test()`]: https://t-regx.com/docs/match#test-a-subject

[`pattern()->test()`]: https://t-regx.com/docs/match#test-a-subject

[`fails()`]: https://t-regx.com/docs/match#test-a-subject

[`Match.parseInt()`]: https://t-regx.com/docs/match-as-int

[`Match.toInt()`]: https://t-regx.com/docs/match-as-int

[`toInt()`]: https://t-regx.com/docs/match-as-int

[`isInt()`]: https://t-regx.com/docs/match-as-int

[`Match.text()`]: https://t-regx.com/docs/match-details#matched-text

[`by()->group()->orElse()`]: https://t-regx.com/docs/replace-by-group

[`by()->group()->orIgnore()`]: https://t-regx.com/docs/replace-by-group#orelseignore

[`orThrow(string)`]: https://t-regx.com/docs/replace-by-group/#orelsethrow

[`orElseThrow(string)`]: https://t-regx.com/docs/replace-by-group/#orelsethrow

[`orIgnore()`]: https://t-regx.com/docs/replace-by-group/#orelseignore

[`orElseIgnore()`]: https://t-regx.com/docs/replace-by-group/#orelseignore

[`orEmpty()`]: https://t-regx.com/docs/replace-by-group/#orelseempty

[`orElseEmpty()`]: https://t-regx.com/docs/replace-by-group/#orelseempty

[`orReturn(string)`]: https://t-regx.com/docs/replace-by-group/#orelsewithstring

[`orElseWith(string)`]: https://t-regx.com/docs/replace-by-group/#orelsewithstring

[`orElse(callable)`]: https://t-regx.com/docs/replace-by-group/#orelsecallingcallable

[`orElseCalling(callable)`]: https://t-regx.com/docs/replace-by-group/#orelsecallingcallable

[1]: https://t-regx.com/docs/replace-by-map#groups

[2]: https://t-regx.com/docs/replace-match-details

[`get_class()`]: https://www.php.net/manual/en/function.get-class.php

[8]: https://www.php.net/manual/en/control-structures.match.php
