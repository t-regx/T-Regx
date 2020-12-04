<?php
namespace TRegx\CleanRegex\Match;

interface Optional
{
    public function orThrow(string $exceptionClassName = null);

    public function orReturn($substitute);

    public function orElse(callable $substituteProducer);
}
