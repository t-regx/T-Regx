<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Match\Optional;

trait EmptyOptional
{
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
