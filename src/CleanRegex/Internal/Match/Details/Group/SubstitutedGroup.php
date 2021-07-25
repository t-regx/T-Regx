<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;

class SubstitutedGroup
{
    /** @var MatchEntry */
    private $match;
    /** @var GroupEntry */
    private $group;

    public function __construct(MatchEntry $match, GroupEntry $entry)
    {
        $this->match = $match;
        $this->group = $entry;
    }

    public function with(string $replacement): string
    {
        $text = $this->match->getText();
        $matchOffset = $this->group->byteOffset() - $this->match->byteOffset();
        $before = \substr($text, 0, $matchOffset);
        $after = \substr($text, $matchOffset + \strlen($this->group->text()));
        return $before . $replacement . $after;
    }
}
