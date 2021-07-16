<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class FirstKeyStream implements Stream
{
    /** @var string|int */
    private $firstKey;

    public function __construct($firstKey)
    {
        $this->firstKey = $firstKey;
    }

    public function firstKey()
    {
        return $this->firstKey;
    }

    public function first()
    {
        throw new \AssertionError("Failed to assert that stream first value wasn't used");
    }

    public function all(): array
    {
        throw new \AssertionError("Failed to assert that stream feed wasn't used");
    }
}
