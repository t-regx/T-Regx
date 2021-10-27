<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Match\Optional;

trait EmptyOptional
{
    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function map(callable $mapper): Optional
    {
        return $this;
    }
}
