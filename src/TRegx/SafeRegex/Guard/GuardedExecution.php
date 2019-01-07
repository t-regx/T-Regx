<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;

class GuardedExecution
{
    public static function invoke(string $methodName, callable $callback, SuspectedReturnStrategy $strategy = null)
    {
        $invocation = (new GuardedInvoker($methodName, $callback, $strategy))->catch();
        if ($invocation->hasException()) {
            throw $invocation->getException();
        }
        return $invocation->getResult();
    }

    public static function catch(string $methodName, callable $callback): GuardedInvocation
    {
        return (new GuardedInvoker($methodName, $callback))->catch();
    }

    public static function silenced(string $methodName, callable $callback): bool
    {
        $invocation = (new GuardedInvoker($methodName, $callback))->catch();
        return $invocation->hasException();
    }
}
