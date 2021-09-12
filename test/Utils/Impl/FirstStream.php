<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

class FirstStream implements Upstream
{
    /** @var mixed */
    private $firstValue;

    public function __construct($firstValue)
    {
        $this->firstValue = $firstValue;
    }

    public function first()
    {
        return $this->firstValue;
    }

    public function firstKey()
    {
        throw new \AssertionError("Failed to assert that stream key wasn't used");
    }

    public function all(): array
    {
        throw new \AssertionError("Failed to assert that stream feed wasn't used");
    }
}
