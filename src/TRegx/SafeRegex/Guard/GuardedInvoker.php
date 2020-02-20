<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\ExceptionFactory;
use TRegx\SafeRegex\Guard\Strategy\DefaultSuspectedReturnStrategy;
use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;
use function call_user_func;

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

    public function __construct(string $methodName, callable $callback, SuspectedReturnStrategy $strategy = null)
    {
        $this->callback = $callback;
        $this->methodName = $methodName;
        $this->errorsCleaner = new ErrorsCleaner();
        $this->exceptionFactory = new ExceptionFactory($strategy ?? new DefaultSuspectedReturnStrategy(), $this->errorsCleaner);
    }

    public function catch(): array
    {
        $this->errorsCleaner->clear();
        $result = call_user_func($this->callback);
        $exception = $this->exception($result);
        $this->errorsCleaner->clear();

        return [$result, $exception];
    }

    private function exception($result): ?PregException
    {
        return $this->exceptionFactory->retrieveGlobals($this->methodName, $result);
    }
}
