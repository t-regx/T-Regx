<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ThrowFalseNegative extends FalseNegative
{
    use Fails;

    public function __construct()
    {
        parent::__construct(new RawMatchOffset([]));
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
}
