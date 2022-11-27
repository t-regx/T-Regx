<?php
namespace TRegx\SafeRegex\Internal\Errors\Errors;

use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Errors\HostError;

class BothHostError implements HostError
{
    /** @var CompileError */
    private $compile;
    /** @var RuntimeError */
    private $runtime;

    public function __construct(CompileError $compileError, RuntimeError $runtimeError)
    {
        $this->compile = $compileError;
        $this->runtime = $runtimeError;
    }

    public function occurred(): bool
    {
        return true;
    }

    public function clear(): void
    {
        $this->compile->clear();
        $this->runtime->clear();
    }

    /**
     * @param string $methodName
     * @param string|string[] $pattern
     * @return PregException
     */
    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        return $this->compile->getSafeRegexpException($methodName, $pattern);
    }
}
