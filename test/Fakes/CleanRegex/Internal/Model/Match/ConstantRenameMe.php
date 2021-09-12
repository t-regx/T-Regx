<?php
namespace Test\Fakes\CleanRegex\Internal\Model\Match;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;

class ConstantRenameMe implements UsedInCompositeGroups
{
    /** @var array */
    private $texts;

    public function __construct(array $texts)
    {
        $this->texts = $texts;
    }

    public function getGroupsTexts(): array
    {
        return $this->texts;
    }

    public function getGroupsOffsets(): array
    {
        throw new AssertionError("Failed to assert that getGroupsOffsets() wasn't used");
    }
}
