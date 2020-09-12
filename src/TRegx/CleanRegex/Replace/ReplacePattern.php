<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\NotReplacedException;

interface ReplacePattern extends SpecificReplacePattern
{
    public function otherwiseThrowing(string $exceptionClassName = NotReplacedException::class): SpecificReplacePattern;

    public function otherwiseReturning($substitute): SpecificReplacePattern;

    public function otherwise(callable $substituteProducer): SpecificReplacePattern;
}
