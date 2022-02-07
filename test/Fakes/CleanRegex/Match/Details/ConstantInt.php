<?php
namespace Test\Fakes\CleanRegex\Match\Details;

use TRegx\CleanRegex\Match\Details\Intable;

class ConstantInt implements Intable
{
    /** @var int */
    private $integer;
    /** @var int */
    private $expectedBase;

    public function __construct(int $integer, int $expectedBase)
    {
        $this->integer = $integer;
        $this->expectedBase = $expectedBase;
    }

    public function toInt(int $base = null): int
    {
        if ($this->expectedBase === $base) {
            return $this->integer;
        }
        throw new \AssertionError('Failed to assert the expected base');
    }
}
