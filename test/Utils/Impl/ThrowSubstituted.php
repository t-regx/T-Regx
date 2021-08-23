<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;

class ThrowSubstituted extends SubstitutedGroup
{
    public function __construct()
    {
        parent::__construct(new ConstantMatchEntry('', 0), new GroupEntry('', 0, new ThrowSubject()));
    }

    public function with(string $replacement): string
    {
        throw new AssertionError("Failed to assert that SubstitutedGroup wasn't used");
    }
}
