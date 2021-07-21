<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class ThrowStream implements Stream
{
    /** @var \Throwable */
    private $throwable;

    public function __construct(\Throwable $throwable = null)
    {
        $this->throwable = $throwable ?? new AssertionError("Failed to assert that stream wasn't used");
    }

    public function all(): array
    {
        throw $this->throwable;
    }

    public function first()
    {
        throw $this->throwable;
    }

    public function firstKey()
    {
        throw $this->throwable;
    }
}
