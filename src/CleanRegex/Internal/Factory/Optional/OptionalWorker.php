<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;

interface OptionalWorker
{
    public function arguments(): array;

    public function throwable(?string $exceptionClassname): Throwable;
}
