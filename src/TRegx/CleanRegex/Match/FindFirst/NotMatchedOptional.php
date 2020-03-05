<?php
namespace TRegx\CleanRegex\Match\FindFirst;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;

class NotMatchedOptional implements Optional
{
    /** @var NotMatchedWorker */
    private $worker;

    public function __construct(NotMatchedWorker $worker)
    {
        $this->worker = $worker;
    }

    public function orThrow(string $exceptionClassName = SubjectNotMatchedException::class): void
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
