<?php
namespace TRegx\SafeRegex\Internal\Guard;

use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Internal\ExceptionFactory;
use TRegx\SafeRegex\Internal\Guard\Strategy\SuspectedReturnStrategy;

class GuardedInvoker
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;
    /** @var ErrorsCleaner */
    private $errorsCleaner;
    /** @var ExceptionFactory */
    private $exceptionFactory;
    /** @var SuspectedReturnStrategy */
    private $strategy;

    public function __construct(string $methodName, $pattern, callable $callback, SuspectedReturnStrategy $strategy)
    {
        $this->callback = $callback;
        $this->methodName = $methodName;
        $this->errorsCleaner = new ErrorsCleaner();
        $this->strategy = $strategy;
        $this->exceptionFactory = new ExceptionFactory($pattern, $this->strategy, $this->errorsCleaner);
    }

    public function catch(): array
    {
        $this->errorsCleaner->clear();
        $result = ($this->callback)();
        $exception = $this->exceptionFactory->retrieveGlobals($this->methodName, $result);
        $this->errorsCleaner->clear();

        return [$result, $exception];
    }
}
