<?php
namespace TRegx\SafeRegex\Errors;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;
use function array_key_exists;
use function in_array;

class FailureIndicators
{
    /** @var SuspectedReturnStrategy */
    private $strategy;

    public function __construct(SuspectedReturnStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    private $vague = [
        'preg_grep',
        'preg_quote',
    ];

    private static $indicators = [
        'preg_match'                  => [false],
        'preg_match_all'              => [false],
        'preg_replace'                => [null, []],
        'preg_filter'                 => [null, []],
        'preg_replace_callback'       => [null],
        'preg_replace_callback_array' => [null],
        'preg_split'                  => [false],
    ];

    public function suspected(string $methodName, $value): bool
    {
        if ($this->isMethodVague($methodName)) {
            return false;
        }
        if (array_key_exists($methodName, self::$indicators)) {
            return in_array($value, self::$indicators[$methodName], true);
        }
        throw new InternalCleanRegexException();
    }

    private function isMethodVague(string $methodName): bool
    {
        return in_array($methodName, $this->vague, true);
    }
}
