<?php
namespace TRegx\CleanRegex\Internal\Match;

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
        throw $this->worker->throwable($exceptionClassName);
    }

    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer(...$this->worker->arguments());
    }

    public function map(callable $mapper): Optional
    {
        return $this;
    }
}
