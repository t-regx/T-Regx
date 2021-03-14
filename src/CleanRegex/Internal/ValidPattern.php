<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\IdentityStrategy;
use TRegx\SafeRegex\Internal\Guard\GuardedExecution;

class ValidPattern
{
    public static function isValidStandard(string $pattern): bool
    {
        return self::isValid((new Delimiterer(new IdentityStrategy()))->delimiter($pattern));
    }

    public static function isValid(string $pattern): bool
    {
        return !GuardedExecution::silenced('preg_match', static function () use ($pattern) {
            return @\preg_match($pattern, null);
        });
    }
}
