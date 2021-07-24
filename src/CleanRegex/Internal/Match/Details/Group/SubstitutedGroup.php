<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;

class SubstitutedGroup
{
    /** @var MatchEntry */
    private $match;
    /** @var MatchedGroupOccurrence */
    private $occurrence;

    public function __construct(MatchEntry $match, MatchedGroupOccurrence $occurrence)
    {
        $this->match = $match;
        $this->occurrence = $occurrence;
    }

    public function with(string $replacement): string
    {
        $text = $this->match->getText();
        $matchOffset = $this->occurrence->offset - $this->match->byteOffset();
        $before = \substr($text, 0, $matchOffset);
        $after = \substr($text, $matchOffset + \strlen($this->occurrence->text));
        return $before . $replacement . $after;
    }
}
