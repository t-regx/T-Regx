<?php
namespace Test\Fakes\CleanRegex\Match\Details;

class ConstantInt implements \TRegx\CleanRegex\Internal\Match\Intable
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

    public function toInt(int $base = 10): int
    {
        if ($this->expectedBase === $base) {
            return $this->integer;
        }
        throw new \AssertionError('Failed to assert the expected base');
    }
}
