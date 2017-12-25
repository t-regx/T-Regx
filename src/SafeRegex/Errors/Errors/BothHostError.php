<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

class BothHostError implements HostError
{
    /** @var PhpHostError */
    private $php;
    /** @var RuntimeError */
    private $runtime;

    public function __construct(PhpHostError $phpHostError, RuntimeError $runtimeError)
    {
        $this->php = $phpHostError;
        $this->runtime = $runtimeError;
    }

    public function occurred(): bool
    {
        return $this->php->occurred() || $this->runtime->occurred();
    }

    public function clear(): void
    {
        $this->php->occurred() && $this->php->clear();
        $this->runtime->occurred() && $this->runtime->clear();
    }
}
