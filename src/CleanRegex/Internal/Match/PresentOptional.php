<?php
namespace TRegx\CleanRegex\Internal\Match;

use Throwable;
use TRegx\CleanRegex\Match\Optional;

class PresentOptional implements Optional
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function orThrow(Throwable $throwable)
    {
        return $this->value;
    }

    public function orReturn($substitute)
    {
        return $this->value;
    }

    public function orElse(callable $substituteProducer)
    {
        return $this->value;
    }

    public function map(callable $mapper): Optional
    {
        return new PresentOptional($mapper($this->value));
    }
}
