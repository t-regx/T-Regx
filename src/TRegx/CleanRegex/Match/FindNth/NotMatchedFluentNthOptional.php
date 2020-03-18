<?php
namespace TRegx\CleanRegex\Match\FindNth;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Match\FindFirst\Optional;

class NotMatchedFluentNthOptional implements Optional
{
    /** @var NotMatchedWorker */
    private $worker;

    public function __construct(NotMatchedWorker $worker)
    {
        $this->worker = $worker;
    }

    public function orThrow(string $exceptionClassName = NoSuchElementFluentException::class): void
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
