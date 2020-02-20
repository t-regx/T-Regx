<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;

class GuardedExecution
{
    public static function invoke(string $methodName, callable $callback, SuspectedReturnStrategy $strategy = null)
    {
        [$result, $exception] = (new GuardedInvoker($methodName, $callback, $strategy))->catch();
        if ($exception !== null) {
            throw $exception;
        }
        return $result;
    }

    public static function silenced(string $methodName, callable $callback): bool
    {
        [$result, $exception] = (new GuardedInvoker($methodName, $callback))->catch();
        return $exception !== null;
    }
}
