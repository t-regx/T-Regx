<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;

class NotMatchedFluentOptional implements Optional
{
    /** @var NotMatchedWorker */
    private $worker;

    public function __construct(NotMatchedWorker $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws Throwable
     */
    public function orThrow(string $exceptionClassName = NoFirstElementFluentException::class)
    {
        throw $this->worker->orThrow($exceptionClassName);
    }

    /**
     * @param mixed $substitute
     * @return mixed
     */
    public function orReturn($substitute)
    {
        return $substitute;
    }

    /**
     * @param callable $substituteProducer
     * @return mixed
     */
    public function orElse(callable $substituteProducer)
    {
        return $this->worker->orElse($substituteProducer);
    }
}
