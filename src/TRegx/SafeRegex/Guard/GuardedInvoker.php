<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\SafeRegexException;
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
    /** @var ExceptionFactory */
    private $exceptionFactory;

    public function __construct(string $methodName, callable $callback, SuspectedReturnStrategy $strategy = null)
    {
        $this->callback = $callback;
        $this->methodName = $methodName;
        $this->exceptionFactory = new ExceptionFactory($strategy ?? new DefaultSuspectedReturnStrategy());
    }

    public function catch(): GuardedInvocation
    {
        $this->clearErrors();
        $result = call_user_func($this->callback);
        $exception = $this->exception($result);
        $this->clearErrors();

        return new GuardedInvocation($result, $exception);
    }

    private function clearErrors(): void
    {
        (new ErrorsCleaner())->clear();
    }

    private function exception($result): ?SafeRegexException
    {
        return $this->exceptionFactory->retrieveGlobals($this->methodName, $result);
    }
}
