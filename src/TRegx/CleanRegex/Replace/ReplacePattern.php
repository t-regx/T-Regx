<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\NotReplacedException;

interface ReplacePattern extends SpecificReplacePattern
{
    public function throwingOtherwise(string $exceptionClassName = NotReplacedException::class): SpecificReplacePattern;

    public function returningOtherwise($substitute): SpecificReplacePattern;

    public function otherwise(callable $substituteProducer): SpecificReplacePattern;
}
