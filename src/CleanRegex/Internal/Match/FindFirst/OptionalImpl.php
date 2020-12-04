<?php
namespace TRegx\CleanRegex\Internal\Match\FindFirst;

use TRegx\CleanRegex\Match\Optional;

class OptionalImpl implements Optional
{
    /** @var mixed */
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function orThrow(string $exceptionClassName = null)
    {
        return $this->result;
    }

    public function orReturn($substitute)
    {
        return $this->result;
    }

    public function orElse(callable $substituteProducer)
    {
        return $this->result;
    }
}
