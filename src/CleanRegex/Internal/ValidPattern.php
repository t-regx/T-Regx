<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\SafeRegex\Internal\Guard\GuardedExecution;

class ValidPattern
{
    public static function isValidStandard(string $pattern): bool
    {
        return self::isValid(Delimiter::suitable($pattern)->delimited(new PatternWord($pattern), new Flags('')));
    }

    public static function isValid(string $pattern): bool
    {
        return !GuardedExecution::silenced('preg_match', static function () use ($pattern) {
            return @\preg_match($pattern, '');
        });
    }
}
