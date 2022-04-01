<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Details\Group;

use AssertionError;
use Test\Fakes\CleanRegex\Internal\Pcre\Legacy\ThrowFactory;
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
