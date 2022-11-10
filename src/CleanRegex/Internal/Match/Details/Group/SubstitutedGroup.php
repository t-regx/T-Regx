<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Model\Entry;

class SubstitutedGroup
{
    /** @var Entry */
    private $matchEntry;
    /** @var GroupEntry */
    private $groupEntry;

    public function __construct(Entry $matchEntry, GroupEntry $groupEntry)
    {
        $this->matchEntry = $matchEntry;
        $this->groupEntry = $groupEntry;
    }

    public function with(string $replacement): string
    {
        $text = $this->matchEntry->text();
        $matchOffset = $this->groupEntry->byteOffset() - $this->matchEntry->byteOffset();
        $before = \subStr($text, 0, $matchOffset);
        $after = \subStr($text, $matchOffset + \strLen($this->groupEntry->text()));
        return $before . $replacement . $after;
    }
}
