<?php
namespace TRegx\SafeRegex\Internal\Errors\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Constants\PregConstants;
use TRegx\SafeRegex\Internal\Errors\HostError;
use TRegx\SafeRegex\Internal\Exception\Factory\RuntimePregExceptionFactory;
use function preg_match;

class RuntimeError implements HostError
{
    /** @var int */
    private $pregError;

    /** @var string */
    private $pregConstant;

    public function __construct(int $pregError)
    {
        $this->pregError = $pregError;
        $this->pregConstant = (new PregConstants())->getConstant($pregError);
    }

    public function occurred(): bool
    {
        return $this->pregError !== \PREG_NO_ERROR;
    }

    public function clear(): void
    {
        preg_match('//', '');
    }

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
