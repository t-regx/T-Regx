<?php
namespace Test\Utils\Impl;

use AssertionError;
use Throwable;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ThrowFalseNegative extends FalseNegative
{
    public function __construct()
    {
        parent::__construct(new RawMatchOffset([], null));
    }

    private function fail(): Throwable
    {
        return new AssertionError("Failed to assert that FalseNegative wasn't used");
    }

    public function maybeGroupIsMissing($nameOrIndex): bool
    {
        return true;
    }

    public function getGroupKeys(): array
    {
        throw $this->fail();
    }

    public function text(): string
    {
        throw $this->fail();
    }

    public function byteOffset(): int
    {
        throw $this->fail();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        throw $this->fail();
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        throw $this->fail();
    }

    public function getGroupsTexts(): array
    {
        throw $this->fail();
    }

    public function getGroupsOffsets(): array
    {
        throw $this->fail();
    }
}
