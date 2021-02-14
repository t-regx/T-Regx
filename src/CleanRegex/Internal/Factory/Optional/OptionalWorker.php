<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;

interface OptionalWorker
{
    public function orElse(callable $producer);

    public function orThrow(?string $exceptionClassname): Throwable;
}
