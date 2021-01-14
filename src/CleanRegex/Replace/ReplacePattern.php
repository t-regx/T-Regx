<?php
namespace TRegx\CleanRegex\Replace;

interface ReplacePattern extends CompositeReplacePattern
{
    public function otherwiseThrowing(string $exceptionClassName = null): CompositeReplacePattern;

    public function otherwiseReturning($substitute): CompositeReplacePattern;

    public function otherwise(callable $substituteProducer): CompositeReplacePattern;

    public function counting(callable $countReceiver): CompositeReplacePattern;
}
