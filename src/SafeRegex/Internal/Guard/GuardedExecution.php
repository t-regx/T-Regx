<?php
namespace TRegx\SafeRegex\Internal\Guard;

use TRegx\SafeRegex\Internal\Guard\Strategy\SuspectedReturnStrategy;

class GuardedExecution
{
    public static function invoke(string $methodName, $pattern, callable $callback, SuspectedReturnStrategy $strategy = null)
    {
        [$result, $exception] = (new GuardedInvoker($methodName, $pattern, $callback, $strategy))->catch();
        if ($exception !== null) {
            throw $exception;
        }
        return $result;
    }

    public static function silenced(string $methodName, callable $callback): bool
    {
        [$result, $exception] = (new GuardedInvoker($methodName, null, $callback))->catch();
        return $exception !== null;
    }
}
