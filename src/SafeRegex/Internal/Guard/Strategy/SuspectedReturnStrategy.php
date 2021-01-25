<?php
namespace TRegx\SafeRegex\Internal\Guard\Strategy;

interface SuspectedReturnStrategy
{
    public function isSuspected(string $methodName, $result): bool;
}
