<?php
namespace TRegx\SafeRegex\Internal\Guard\Strategy;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class DefaultSuspectedReturnStrategy implements SuspectedReturnStrategy
{
    /**
     * @var array<string, null|false>
     */
    private static $indicators = [
        'preg_match'                  => false,
        'preg_match_all'              => false,
        'preg_replace_callback'       => null,
        'preg_replace_callback_array' => null,
        'preg_split'                  => false,
    ];

    public function isSuspected(string $methodName, $result): bool
    {
        if (\array_key_exists($methodName, self::$indicators)) {
            return $result === self::$indicators[$methodName];
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
