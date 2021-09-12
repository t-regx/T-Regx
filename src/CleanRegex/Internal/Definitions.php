<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Identity;
use TRegx\CleanRegex\Internal\Expression\Standard;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Pattern;

class Definitions
{
    public static function composed(array $patterns, callable $patternDefinition): array
    {
        return \iterator_to_array(self::definitions($patterns, $patternDefinition));
    }

    private static function definitions(array $patterns, callable $patternDefinition): Generator
    {
        foreach ($patterns as $pattern) {
            yield self::expression($pattern, $patternDefinition)->definition();
        }
    }

    private static function expression($pattern, callable $patternDefinition): Expression
    {
        if (\is_string($pattern)) {
            return new Standard($pattern, '');
        }
        if ($pattern instanceof Pattern) {
            return new Identity($patternDefinition($pattern));
        }
        throw InvalidArgument::typeGiven("CompositePattern only accepts type Pattern or string", new ValueType($pattern));
    }
}
