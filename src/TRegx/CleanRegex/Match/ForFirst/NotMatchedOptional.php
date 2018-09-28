<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;

class NotMatchedOptional implements Optional
{
    /** @var NotMatchedOptionalWorker */
    private $worker;

    public function __construct(NotMatchedOptionalWorker $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws Throwable
     */
    public function orThrow(string $exceptionClassName = SubjectNotMatchedException::class)
    {
        throw $this->worker->orThrow($exceptionClassName);
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default)
    {
        return $default;
    }

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer)
    {
        return $this->worker->orElse($producer);
    }
}
