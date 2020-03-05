<?php
namespace TRegx\CleanRegex\Match\FindFirst;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

class MatchedOptional implements Optional
{
    /** @var mixed */
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function orThrow(string $exceptionClassName = SubjectNotMatchedException::class)
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
