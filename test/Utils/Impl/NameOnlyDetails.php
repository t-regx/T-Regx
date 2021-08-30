<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;

class NameOnlyDetails extends GroupDetails
{
    public function __construct(string $name)
    {
        parent::__construct(new GroupSignature(0, null), new GroupName($name), new ThrowFactory());
    }

    public function all(): array
    {
        throw $this->fail();
    }

    public function nameOrIndex()
    {
        throw $this->fail();
    }

    public function name(): ?string
    {
        throw $this->fail();
    }

    public function index(): int
    {
        throw $this->fail();
    }

    private function fail(): AssertionError
    {
        return new AssertionError("Failed to assert that GroupDetails wasn't used");
    }
}
