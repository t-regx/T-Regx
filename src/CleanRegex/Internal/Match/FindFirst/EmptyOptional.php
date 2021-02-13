<?php
namespace TRegx\CleanRegex\Internal\Match\FindFirst;

use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Match\Optional;

class EmptyOptional implements Optional
{
    /** @var OptionalWorker */
    private $worker;

    public function __construct(OptionalWorker $worker)
    {
        $this->worker = $worker;
    }

    public function orThrow(string $exceptionClassName = null): void
    {
        throw $this->worker->orThrow($exceptionClassName);
    }

    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function orElse(callable $substituteProducer)
    {
        return $this->worker->orElse($substituteProducer);
    }
}
