<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;

class ThrowGroupDetails extends GroupDetails
{
    public function __construct()
    {
        parent::__construct(new GroupSignature(0, null), new GroupIndex(0), new ThrowFactory());
    }

    public function all(): array
    {
        throw new AssertionError("Failed to assert that GroupDetails weren't used");
    }
}
