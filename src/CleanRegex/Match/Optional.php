<?php
namespace TRegx\CleanRegex\Match;

use Throwable;

interface Optional
{
    public function get();

    public function orThrow(Throwable $throwable);

    public function orReturn($substitute);

    public function orElse(callable $substituteProducer);

    public function map(callable $mapper): Optional;
}
