<?php
namespace Test\Utils;

use AssertionError;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Expression\Standard;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

class Definitions
{
    public static function pattern(string $pattern, string $flags = null): Definition
    {
        if (TrailingBackslash::hasTrailingSlash($pattern)) {
            throw new AssertionError();
        }
        /**
         * I intentionally don't use {@see Standard}, because if there are bugs in it,
         * then the tests are compromised. By using low-level {@see Delimiter} and
         * {@see PatternPhrase} to reduce the posibilities of false-positives in tests.
         */
        return new Definition(self::candidates($pattern)->delimiter()->delimited(new PatternPhrase($pattern), new Flags($flags ?? '')), $pattern);
    }

    private static function candidates(string $delimiterable): Candidates
    {
        return new Candidates(new UnsuitableStringCondition($delimiterable));
    }

    public static function pcre(string $pattern): Definition
    {
        return new Definition($pattern, $pattern);
    }
}
