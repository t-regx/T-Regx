<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Exception\EmptyOptionalException;
use TRegx\CleanRegex\Match\Optional;

class EmptyOptional implements Optional
{
    public function get()
    {
        throw new EmptyOptionalException();
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
