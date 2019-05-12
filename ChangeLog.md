T-Regx Changelog
================

This file is to keep track of enhancements and bug fixes in different versions of T-Regx.

Added in 1.0
---------------

* Features
    * Pass flags as `pattern()` second argument
    * Add `Match.groups()` and `Match.limit()`
    * Add `Match.group()->all()` 
    * Add `Match.getUserData()`/`setUserData()` 
    * Add `ReplaceMatch.modifiedSubject()`
    * Returning from `match()->first(callable)` modifies its return type
    * Add `pattern()->remove()`
    * Add `pattern()->replace()->by()`
    * Add `match()->only(int)`
    * Add `match()->flatMap()`
    * Add `match()->group()->all()`, `match()->group()->first()` and `match()->group()->only()`
    * Add `match()->iterator()`
    * Add `match()->forFirst()`
        * with methods `orReturn()`, `orElse()` and `orThrow()`
        * `orThrow()` can instantiate exceptions by class name (with one of predefined constructor signatures)
    * `match->only(i)` calls `preg_match()` for `i=1`, and `preg_match_all()` for other values
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
    * Add test for `ReplacePatternAll`, `ErrorsCleaner.getError()`, `ValidPatternTest.shouldNotLeaveErrors()`,
 `GuardedExecution.silenced()`, `GuardedExecutionTest.shouldNotCatchException()`, `FailureIndicators`,
      `ReplaceCallbackObject`, `ReplacePatternCallbackInvoker` from 1.0.
    * Handle [PHP bugfix in 7.1.13](https://bugs.php.net/bug.php?id=74183).
* Debug
    * Add `pregConstant` field to `RuntimeError`. Only reason to do it is so if you **catch the exception it 
    in debugger**, you'll see constant name (ie. `PREG_BAD_UTF8_ERROR`) instead of constant value (ie. `4`).
* Other
    * Set `\TRegx` namespace prefix
    * Add `ext-mbstring` requirement to `composer.json`.
    * `preg_match()` won't return unmatched groups at the end of list, which makes validating groups and general
      work with group names impossible. Thanks to `GroupPolyfillDecorator`, a second call to `preg_match_all()` is done
      to get a list of all groups (even unmatched ones). The call to `preg_match_all()` is of course only in the case
      of `Match.hasGroup()` or similar method. Regular methods like `Match.text()` won't call `preg_match_all()`
    * Handle bug [PHP #75355](https://bugs.php.net/bug.php?id=75355)
* Bug fixes
    * `preg::replace()` and `preg::filter()` only consider `[]` an error prone if input subject was also an empty array.

API
---------------

* SafeRegex
    * Create exact copies of `preg_*()` methods: `preg::match()`, `preg::match_all()`, `preg::replace()`, `preg::replace_callback()`, 
      `preg::replace_callback_array()`, `preg::filter()`, `preg::split()`, `preg::grep()`, `preg::quote()`,
      `preg::last_error()` methods.
    * `preg::*` SafeRegex methods never emit warnings or errors, but throw `SafeRegexException` instead.
    * Add additional utility methods:
         * `preg::last_error_constant()`, which returns error constant as string
           (ie. `'PREG_RECURSION_LIMIT_ERROR'`), where as `preg_last_error()` and `preg::last_error()` return constant
           as integer (ie. `3`).
         * `preg::error_constant(int)` method to change error constant from integer to string
           (ie. `preg::error_constant(4) == 'PREG_BAD_UTF8_ERROR'`).
    * `preg::quote()` quotes additional PCRE characters, which `preg_quote()` does not.

* CleanRegex
    * Automatic delimiter (ie. `pattern('[A-Z]')`)
    * Matching API
        * `pattern()->test()`
        * `pattern()->fails()`
        * `pattern()->match()`
            * `->test()`
            * `->fails()`
            * `->all()`
            * `->first()`
            * `->only()`
            * `->map()`
            * `->flatMap()`
            * `->forEach()` / `iterate()`
            * `->iterator()`
            * `->count()`
            * `->offsets()`
                * `->first()`
                * `->all()`
                * `->only(int)`
            * `->group(name|index)`
                * `->first()`
                * `->all()`
                * `->only(int)`
                * `->offsets()`
                    * `->first()`
                    * `->all()`
                    * `->only(int)`
            * `->forFirst()`
                * `->orReturn(mixed)`
                * `->orElse(callable)`
                * `->orThrow(className|null)`
            * `->filter()->*`
        * `Match` details:
            * `Match->text()` / `Match->__toString()` / `(string) $match`
            * `Match->parseInt()`, `Match->isInt()`
            * `Match->subject()`
            * `Match->index()`
            * `Match->limit()`
            * `Match->offset()`
            * `Match->byteOffset()`
            * `Match->group(string|int)`
                * `->text()`
                * `->parseInt()`, `->isInt()`
                * `->matched()`
                * `->name()`
                * `->index()`
                * `->usedIdentifier()`
                * `->offset()`
                * `->byteOffset()`
                * `->all()`
                * `->orThrow()`
                * `->orReturn()`
                * `->orElse()`
            * `Match->groups()->*`, `Match->namedGroups()->*`
                * `->texts()`
                * `->offsets()`
                * `->byteOffsets()`
            * `Match->groupNames()`
            * `Match->matched(string|int)`
            * `Match->hasGroup(string|int)`
            * `Match->all()`
            * `Match->setUserData()`, `Match->getUserData()`
        * `NotMatched` details
            * `NotMatched->subject()`
            * `NotMatched->groupNames()`
            * `NotMatched->groupsCount()`
            * `NotMatched->hasGroup(string|int)`
    * Replace API
        * `pattern()->replace()->all()`...
        * `pattern()->replace()->first()`...
        * `pattern()->replace()->only(int)`....
            * `->with()`
            * `->withReferences()`
            * `->callback()`
            * `->by()`/`by()->group()`
                * `->map()`
                * `->mapIfExists()`
                * `->mapOrDefault()`
        * `ReplaceMatch` details (extending `Match` details)
            * `ReplaceMatch.modifiedOffset()`
            * `ReplaceMatch.modifiedSubject()`
    * Remove API
        * `pattern()->remove()->all()`
        * `pattern()->remove()->first()`
        * `pattern()->remove()->only(int)`
    * Other API
        * `pattern()->forArray()`
            * `->filter()`
            * `->filterAssoc()`
            * `->filterByKeys()`
        * `pattern()->split()->ex()`
        * `pattern()->split()->inc()`
        * `pattern()->split()->filter()->*`
        * `pattern()->count()`
        * `pattern()->is()->valid()`
        * `pattern()->is()->usable()`
        * `pattern()->is()->delimitered()`
        * `pattern()->delimitered()`
        * `Pattern::quote()`
        * `Pattern::unquote()`
    * Building Pattern API
        * `Pattern::of()`
        * `Pattern::inject()`/`PatternBuilder::inject()`
        * `Pattern::prepare()`/`PatternBuilder::prepare()`
        * `Pattern::compose()`/`PatternBuilder::compose()`
