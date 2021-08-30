<?php
namespace Test\Utils\Impl;

use Throwable;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

class ConstantThrowOptionalWorker implements OptionalWorker
{
    /** @var Throwable */
    private $throwable;

    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public function throwable(?string $exceptionClassname): Throwable
    {
        throw $this->throwable;
    }

    public function arguments(): array
    {
        throw new \Exception("arguments() wasn't expected to be called");
    }
}
