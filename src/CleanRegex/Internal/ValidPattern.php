<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\AutomaticDelimiter;
use TRegx\SafeRegex\Internal\Guard\GuardedExecution;

class ValidPattern
{
    public static function isValidStandard(string $pattern): bool
    {
        return self::isValid(AutomaticDelimiter::standard($pattern, ''));
    }

    public static function isValid(string $pattern): bool
    {
        return !GuardedExecution::silenced('preg_match', static function () use ($pattern) {
            return @\preg_match($pattern, null);
        });
    }
}
