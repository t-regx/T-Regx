<?php
namespace Test\Utils;

use Throwable;
use TRegx\CleanRegex\Internal\Factory\OptionalWorker;

class ThrowWorker implements OptionalWorker
{
    /** @var \Exception|null */
    private $firstElementException;

    public function __construct(\Exception $firstElementException = null)
    {
        $this->firstElementException = $firstElementException;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        throw new \Exception();
    }

    public function orElse(callable $producer)
    {
        throw new \Exception();
    }

    public function noFirstElementException(): \Exception
    {
        if ($this->firstElementException === null) {
            throw new \Exception();
        }
        throw $this->firstElementException;
    }

    public function chainWorker(): OptionalWorker
    {
        return $this;
    }

    public function optionalDefaultClass(): string
    {
        throw new \Exception();
    }
}
