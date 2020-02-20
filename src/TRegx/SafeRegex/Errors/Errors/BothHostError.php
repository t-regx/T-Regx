<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\SafeRegex\Errors\HostError;
use TRegx\SafeRegex\Exception\PregException;

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
        return $this->compile->occurred() || $this->runtime->occurred();
    }

    public function clear(): void
    {
        $this->compile->occurred() && $this->compile->clear();
        $this->runtime->occurred() && $this->runtime->clear();
    }

    public function getSafeRegexpException(string $methodName): PregException
    {
        return $this->compile->getSafeRegexpException($methodName);
    }
}
