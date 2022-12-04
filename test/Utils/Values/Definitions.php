<?php
namespace Test\Utils\Values;

use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

/**
 * @deprecated
 */
class Definitions
{
    /**
     * @deprecated
     */
    public static function pattern(string $pattern, string $flags = null): Definition
    {
        /**
         * I intentionally don't use {@see Standard}, because if there are bugs in it,
         * then the tests are compromised. By using low-level {@see Delimiter} and
         * {@see PatternPhrase} to reduce the possibilities of false-positives in tests.
         */
        return new Definition(self::candidates($pattern)->delimiter()->delimited(new PatternPhrase($pattern), Flags::from($flags)));
    }

    private static function candidates(string $delimitable): Candidates
    {
        return new Candidates(new UnsuitableStringCondition($delimitable));
    }

    public static function pcre(string $pattern): Definition
    {
        return new Definition($pattern);
    }
}
