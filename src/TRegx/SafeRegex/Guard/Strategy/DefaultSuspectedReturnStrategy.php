<?php
namespace TRegx\SafeRegex\Guard\Strategy;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use function array_key_exists;

class DefaultSuspectedReturnStrategy implements SuspectedReturnStrategy
{
    private static $indicators = [
        'preg_match'                  => false,
        'preg_match_all'              => false,
        'preg_replace_callback'       => null,
        'preg_replace_callback_array' => null,
        'preg_split'                  => false,
    ];

    public function isSuspected(string $methodName, $result): bool
    {
        if (array_key_exists($methodName, self::$indicators)) {
            return $result === self::$indicators[$methodName];
        }
        throw new InternalCleanRegexException();
    }
}
