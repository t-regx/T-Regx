<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\SafeRegex\Guard\GuardedExecution;
use function preg_match;

class ValidPattern
{
    public static function isValid(string $pattern): bool
    {
        return !GuardedExecution::silenced('preg_match', static function () use ($pattern) {
            return @preg_match($pattern, null);
        });
    }
}
