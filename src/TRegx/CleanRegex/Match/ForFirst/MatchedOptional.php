<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;

class MatchedOptional implements Optional
{
    /** @var mixed */
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     */
    public function orThrow(string $exceptionClassName = SubjectNotMatchedException::class)
    {
        return $this->result;
    }

    /**
     * @param mixed $substitute
     * @return mixed
     */
    public function orReturn($substitute)
    {
        return $this->result;
    }

    /**
     * @param callable $substituteProducer
     * @return mixed
     */
    public function orElse(callable $substituteProducer)
    {
        return $this->result;
    }
}
