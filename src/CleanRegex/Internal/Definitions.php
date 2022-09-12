<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Identity;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Pattern;

class Definitions
{
    public static function composed(array $patterns, callable $patternDefinition): Predefinitions
    {
        return new Predefinitions(\iterator_to_array(self::predefinitions($patterns, $patternDefinition)));
    }

    private static function predefinitions(array $patterns, callable $patternDefinition): Generator
    {
        foreach ($patterns as $pattern) {
            yield self::expression($pattern, $patternDefinition)->predefinition();
        }
    }

    private static function expression($pattern, callable $patternDefinition): Expression
    {
        if (\is_string($pattern)) {
            return new Standard(new StandardSpelling($pattern, Flags::empty(), new UnsuitableStringCondition($pattern)));
        }
        if ($pattern instanceof Pattern) {
            return new Identity($patternDefinition($pattern));
        }
        throw InvalidArgument::typeGiven("CompositePattern only accepts type Pattern or string", new ValueType($pattern));
    }
}
