<?php
namespace SafeRegex\Guard;

use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\ExceptionFactory;

class GuardedInvoker
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;

    public function __construct(string $methodName, callable $callback)
    {
        $this->callback = $callback;
        $this->methodName = $methodName;
    }

    public function catch(): GuardedInvocation
    {
        $this->clearObsoleteCompileAndRuntimeErrors();

        $result = call_user_func($this->callback);

        return new GuardedInvocation($result, $this->exception($result));
    }

    private function clearObsoleteCompileAndRuntimeErrors(): void
    {
        (new ErrorsCleaner())->clear();
    }

    private function exception($result): ?SafeRegexException
    {
        return (new ExceptionFactory())->retrieveGlobals($this->methodName, $result);
    }
}
