<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\NotReplacedException;
use TRegx\CleanRegex\Match\FindFirst\Optional;

interface ReplacePattern extends SpecificReplacePattern, Optional
{
    public function orThrow(string $exceptionClassName = NotReplacedException::class): SpecificReplacePattern;

    public function orReturn($substitute): SpecificReplacePattern;

    public function orElse(callable $substituteProducer): SpecificReplacePattern;
}
