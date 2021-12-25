<?php
namespace Test\Fakes\CleanRegex\Internal\Model\Match;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class ConstantEntries implements GroupEntries
{
    /** @var array */
    private $texts;

    public function __construct(array $texts)
    {
        $this->texts = $texts;
    }

    public function groupTexts(): array
    {
        return $this->texts;
    }

    public function groupOffsets(): array
    {
        throw new AssertionError("Failed to assert that getGroupsOffsets() wasn't used");
    }
}
