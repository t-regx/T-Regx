<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Identity;
use TRegx\CleanRegex\Internal\Expression\Standard;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Pattern;

class InternalPatterns
{
    public static function compose(array $patterns, callable $patternToInternal): array
    {
        return \iterator_to_array(self::internalPatterns($patterns, $patternToInternal));
    }

    private static function internalPatterns(array $patterns, callable $patternToInternal): Generator
    {
        foreach ($patterns as $pattern) {
            yield self::patternToOutput($pattern, $patternToInternal)->definition();
        }
    }

    private static function patternToOutput($pattern, callable $patternToInternal): Expression
    {
        if (\is_string($pattern)) {
            return new Standard($pattern, '');
        }
        if ($pattern instanceof Pattern) {
            return new Identity($patternToInternal($pattern));
        }
        throw InvalidArgument::typeGiven("CompositePattern only accepts type Pattern or string", new ValueType($pattern));
    }
}
