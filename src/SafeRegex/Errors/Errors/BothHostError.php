<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

class BothHostError implements HostError
{
    /** @var PhpHostError */
    private $php;
    /** @var PregHostError */
    private $preg;

    public function __construct(PhpHostError $phpHostError, PregHostError $pregHostError)
    {
        $this->php = $phpHostError;
        $this->preg = $pregHostError;
    }

    public function occurred(): bool
    {
        return $this->php->occurred() || $this->preg->occurred();
    }

    public function clear(): void
    {
        $this->php->occurred() && $this->php->clear();
        $this->preg->occurred() && $this->preg->clear();
    }
}
