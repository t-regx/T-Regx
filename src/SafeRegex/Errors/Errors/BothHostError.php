<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

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
}
