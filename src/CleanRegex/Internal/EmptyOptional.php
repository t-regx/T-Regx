<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Match\Optional;

class EmptyOptional implements Optional
{
    /** @var Throwable */
    private $throwable;

    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public function get()
    {
        throw $this->throwable;
    }

    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer();
    }

    public function orThrow(Throwable $throwable): void
    {
        throw $throwable;
    }

    public function map(callable $mapper): Optional
    {
        return $this;
    }
}
