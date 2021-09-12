<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Details\Group;

use AssertionError;
use Test\Fakes\CleanRegex\Internal\Model\Match\ConstantEntry;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;

class ThrowSubstituted extends SubstitutedGroup
{
    public function __construct()
    {
        parent::__construct(new ConstantEntry('', 0), new GroupEntry('', 0, new ThrowSubject()));
    }

    public function with(string $replacement): string
    {
        throw new AssertionError("Failed to assert that SubstitutedGroup wasn't used");
    }
}
