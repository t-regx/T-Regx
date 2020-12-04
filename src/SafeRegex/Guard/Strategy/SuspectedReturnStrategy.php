<?php
namespace TRegx\SafeRegex\Guard\Strategy;

interface SuspectedReturnStrategy
{
    public function isSuspected(string $methodName, $result): bool;
}
