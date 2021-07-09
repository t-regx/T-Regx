<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Prepared\Expression\Expression;
use TRegx\CleanRegex\Internal\Prepared\Expression\Identity;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
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
        throw self::invalidArgumentException($pattern);
    }

    private static function invalidArgumentException($invalidArgument): \InvalidArgumentException
    {
        $type = Type::asString($invalidArgument);
        return new \InvalidArgumentException("CompositePattern only accepts type Pattern or string, but $type given");
    }
}
