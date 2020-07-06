<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

/**
 * To future developers of this application, or whom it may concern:
 *
 * As of this moment, `null` doesn't mean `null` as far as this interface
 * is concerned. Bear with me.
 *
 * Certain implementations of this interface receive a non-nullable collection
 * of input values (passing collections with `null` to those implementations
 * would result in an exception).
 *
 * Yet, the return of {@link GroupMapper::map()} may return `null`. And that
 * `null` is NOT the same as the `null` that would be passed to the implementations
 * of this interface. `null` returned from {@link GroupMapper::map()}
 * means: "There is no such value" (missing key, if you will), rather
 * than that collection [key => null] was passed, and {@link GroupMapper::map()}
 * was called with key.
 *
 * Example (imagine Impl is an implementation of {@link GroupMapper}:
 *   - Impl(['k' => null]).map('k')      - Exception: `Impl` doesn't allow `null` values.
 *   - Impl(['k' => 'v').map('missing')  - returns `null` (as if "no value")
 */
interface GroupMapper
{
    public function map(string $occurrence): ?string;

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void;
}
