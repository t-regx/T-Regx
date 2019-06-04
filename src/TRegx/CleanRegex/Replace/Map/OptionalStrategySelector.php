<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Match\ForFirst\Optional;

interface OptionalStrategySelector extends Optional
{
    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|GroupNotMatchedException
     */
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class);
}
