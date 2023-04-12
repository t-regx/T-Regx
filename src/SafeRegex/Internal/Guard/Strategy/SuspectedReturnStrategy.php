<?php
namespace TRegx\SafeRegex\Internal\Guard\Strategy;

interface SuspectedReturnStrategy
{
    /**
     * @param mixed $result
     */
    public function isSuspected(string $methodName, $result): bool;
}
