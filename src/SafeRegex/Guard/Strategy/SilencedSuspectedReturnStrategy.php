<?php
namespace TRegx\SafeRegex\Guard\Strategy;

class SilencedSuspectedReturnStrategy implements SuspectedReturnStrategy
{
    public function isSuspected(string $methodName, $result): bool
    {
        return false;
    }
}
