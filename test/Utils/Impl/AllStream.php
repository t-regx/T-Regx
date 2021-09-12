<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

class AllStream implements Upstream
{
    /** @var array */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function all(): array
    {
        return $this->values;
    }

    public function first()
    {
        throw new \AssertionError("Failed to assert that stream first value wasn't used");
    }

    public function firstKey()
    {
        throw new \AssertionError("Failed to assert that stream key wasn't used");
    }
}
