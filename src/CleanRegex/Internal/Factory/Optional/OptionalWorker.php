<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;

interface OptionalWorker
{
    public function orThrow(?string $exceptionClassName): Throwable;

    public function orElse(callable $producer);
}
