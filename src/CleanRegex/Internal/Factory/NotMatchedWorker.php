<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;

interface NotMatchedWorker
{
    public function orThrow(string $exceptionClassName): Throwable;

    public function orElse(callable $producer);
}
