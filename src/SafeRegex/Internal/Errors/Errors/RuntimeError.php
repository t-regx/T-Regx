<?php
namespace TRegx\SafeRegex\Internal\Errors\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Errors\HostError;
use TRegx\SafeRegex\Internal\Factory\RuntimePregExceptionFactory;

class RuntimeError implements HostError
{
    /** @var int */
    private $pregError;

    public function __construct(int $pregError)
    {
        $this->pregError = $pregError;
    }

    public function occurred(): bool
    {
        return $this->pregError !== \PREG_NO_ERROR;
    }

    public function clear(): void
    {
        \preg_match('//', '');
    }

    /**
     * @param string $methodName
     * @param string|string[] $pattern
     * @return PregException
     */
    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        if ($this->occurred()) {
            return (new RuntimePregExceptionFactory($methodName, $pattern, $this->pregError))->create();
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
