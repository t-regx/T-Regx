T-Regx Changelog
================

Added in 0.9.1
---------------

* Features
    * `Match.textLength()`
    * `Match.group().textLength()`
    * `Match.groupsCount()`
    * Add methods `by()->group()->orIgnore()` and `by()->group()->orElse()`
    * Add method `by()->group()->callback()` which accepts `MatchGroup` as an argument
    * Method `by()->group()->orElse()` now receives lazy-loaded `Match`, instead of a subject

Available in 0.9.0
---------------

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
    * Handle [PHP bugfix in 7.1.13](https://bugs.php.net/bug.php?id=74183).
* Other
    * Set `\TRegx` namespace prefix
    * Add `ext-mbstring` requirement to `composer.json`.
    * `preg_match()` won't return unmatched groups at the end of list, which makes validating groups and general
      work with group names impossible. Thanks to `GroupPolyfillDecorator`, a second call to `preg_match_all()` is done
      to get a list of all groups (even unmatched ones). The call to `preg_match_all()` is of course only in the case
      of `Match.hasGroup()` or similar method. Regular methods like `Match.text()` won't call `preg_match_all()`
* Debug
    * Add `pregConstant` field to `RuntimeError`. Only reason to do it is so if you **catch the exception it 
    in debugger**, you'll see constant name (ie. `PREG_BAD_UTF8_ERROR`) instead of constant value (ie. `4`).
    * Handle bug [PHP #75355](https://bugs.php.net/bug.php?id=75355)
* Bug fixes
    * `preg::replace()` and `preg::filter()` only consider `[]` error prone if input subject was also an empty array.

API
---------------

* SafeRegex
    * Exact copies of `preg_*()` functions - wrapper methods that catch warnings and throw `SafeRegexException` instead: 
      * `preg::match()`, 
      * `preg::match_all()`,
      * `preg::replace()`, 
      * `preg::replace_callback()`, 
      * `preg::replace_callback_array()`,
      * `preg::filter()`, 
      * `preg::split()`,
      * `preg::grep()`, 
      * `preg::quote()`,
      * `preg::last_error()`
    * Additional utility methods:
      * `preg::last_error_constant()` - which returns error constant as string
        (ie. `'PREG_RECURSION_LIMIT_ERROR'`), where as `preg_last_error()` and `preg::last_error()` return constant
        as integer (ie. `3`).
      * `preg::error_constant(int)` - method to change error constant from integer to string
        (ie. `preg::error_constant(4) == 'PREG_BAD_UTF8_ERROR'`).
    * Bug fixes:
      * `preg::quote()` quotes additional PCRE characters, which `preg_quote()` does not.

* CleanRegex
    * Matching
        * `pattern()->test()`
        * `pattern()->fails()`
        * `pattern()->match()`
            * `->test()`
            * `->fails()`
            * `->all()` / `->first()` / `->only(int)`
            * `->forEach()` / `iterate()`
            * `->first(callable)`
            * `->map()`
            * `->flatMap()`
            * `->iterator()`
            * `->count()`
            * `->offsets()`
                * `->all()` / `->first()` / `->only(int)`
            * `->group(name|index)`
                * `->all()` / `->first()` / `->only(int)`
                * `->offsets()->*`
            * `->forFirst()`
                * `->orReturn(mixed)`
                * `->orElse(callable)`
                * `->orThrow(className|null)`
            * `->filter()->*`
        * `Match` details:
            * `Match->text()` / `Match->__toString()` / `(string) $match`
            * `Match->textLength()`
            * `Match->parseInt()`, `Match->isInt()`
            * `Match->subject()`
            * `Match->index()`
            * `Match->limit()`
            * `Match->offset()` / `Match->byteOffset()`
            * `Match->group(string|int)`
                * `->text()`
                * `->parseInt()`, `->isInt()`
                * `->matched()`
                * `->name()`
                * `->index()`
                * `->usedIdentifier()`
                * `->offset()` / `->byteOffset()`
                * `->all()`
                * `->orThrow()`
                * `->orReturn()`
                * `->orElse()`
            * `Match->groups()` / `Match->namedGroups()`
                * `->texts()`
                * `->offsets()` / `->byteOffsets()`
            * `Match->groupNames()`
            * `Match->groupsCount()`
            * `Match->matched(string|int)`
            * `Match->hasGroup(string|int)`
            * `Match->all()`
            * `Match->setUserData()`, `Match->getUserData()`
        * `NotMatched` details
            * `NotMatched->subject()`
            * `NotMatched->groupNames()`
            * `NotMatched->groupsCount()`
            * `NotMatched->hasGroup(string|int)`
    * Replace
        * `pattern()->replace()`
            * `->all()` / `->first()` / `->only(int)`
                * `->with()`
                * `->withReferences()`
                * `->callback()`
                    * `ReplaceMatch` details (extending `Match` details)
                        * `ReplaceMatch.modifiedOffset()`
                        * `ReplaceMatch.modifiedSubject()`
                * `by()->group()->callback()`
                * `by()->map()`, `by()->group()`, `by()->group()->map()`
                    * `->orReturn()`
                    * `->orElse()`
                    * `->orThrow()`
                    * `->orEmpty()`
                    * `->orIgnore()`
    * Remove
        * `pattern()->remove()`
            * `->all()` / `->first()` / `->only(int)`
    * Other API
        * `pattern()->forArray()`
            * `->filter()`
            * `->filterAssoc()`
            * `->filterByKeys()`
        * `pattern()->split()->ex()` / `->inc()`
            * `->filter()`
        * `pattern()->count()`
        * `pattern()->is()->valid()`
        * `pattern()->is()->usable()`
        * `pattern()->is()->delimitered()`
        * `pattern()->delimiter()`
        * `Pattern::quote()`
        * `Pattern::unquote()`
    * Building Pattern API
        * `Pattern::of()`
        * `Pattern::compose()`/`PatternBuilder::compose()`
    * Handling user input    
        * `Pattern::inject()`/`PatternBuilder::inject()`
        * `Pattern::prepare()`/`PatternBuilder::prepare()`
