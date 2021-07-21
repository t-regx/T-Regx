<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class ConstantStream implements Stream
{
    private $firstValue;
    /** @var array */
    private $allValues;

    public function __construct($firstValue, array $allValues)
    {
        $this->firstValue = $firstValue;
        $this->allValues = $allValues;
    }

    public function all(): array
    {
        return $this->allValues;
    }

    public function first()
    {
        return $this->firstValue;
    }

    public function firstKey()
    {
        throw new \AssertionError("Failed to assert that stream first key wasn't used");
    }
}
