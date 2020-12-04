<?php
namespace TRegx\CleanRegex\Internal\Match\FindFirst;

use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Match\Optional;

class EmptyOptional implements Optional
{
    /** @var NotMatchedWorker */
    private $worker;
    /** @var string */
    private $defaultException;

    public function __construct(NotMatchedWorker $worker, string $defaultException)
    {
        $this->worker = $worker;
        $this->defaultException = $defaultException;
    }

    public function orThrow(string $exceptionClassName = null): void
    {
        throw $this->worker->orThrow($exceptionClassName ?? $this->defaultException);
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
