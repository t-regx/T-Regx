<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class MatchGroupReplacer
{
    public function replaceGroup(IRawMatchOffset $match, MatchedGroupOccurrence $occurrence, string $replacement): string
    {
        $text = $match->getText();
        $matchOffset = $occurrence->offset - $match->byteOffset();
        $before = \substr($text, 0, $matchOffset);
        $after = \substr($text, $matchOffset + \strlen($occurrence->text));
        return $before . $replacement . $after;
    }
}
