<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\Exception\RuntimeSafeRegexException;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\Exception\SuspectedReturnSafeRegexException;

class GuardedExecution
{
    /**
     * @param string   $methodName
     * @param callable $callback
     * @return mixed
     * @throws SafeRegexException
     * @throws CompileSafeRegexException
     * @throws RuntimeSafeRegexException
     * @throws SuspectedReturnSafeRegexException
     * @throws \Exception
     */
    public static function invoke(string $methodName, callable $callback)
    {
        $invocation = (new GuardedInvoker($methodName, $callback))->catch();
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
