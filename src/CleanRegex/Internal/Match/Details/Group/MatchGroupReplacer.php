<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;

class MatchGroupReplacer
{
    public function replaceGroup(MatchEntry $match, MatchedGroupOccurrence $occurrence, string $replacement): string
    {
        $text = $match->getText();
        $matchOffset = $occurrence->offset - $match->byteOffset();
        $before = \substr($text, 0, $matchOffset);
        $after = \substr($text, $matchOffset + \strlen($occurrence->text));
        return $before . $replacement . $after;
    }
}
