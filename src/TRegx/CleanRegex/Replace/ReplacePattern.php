<?php
namespace TRegx\CleanRegex\Replace;

interface ReplacePattern extends SpecificReplacePattern
{
    public function otherwiseThrowing(string $exceptionClassName = null): SpecificReplacePattern;

    public function otherwiseReturning($substitute): SpecificReplacePattern;

    public function otherwise(callable $substituteProducer): SpecificReplacePattern;
}
