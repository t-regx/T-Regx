<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;

class OffsetsStrategy implements Strategy
{
    /** @var Subjectable */
    private $subjectable;
    /** @var bool */
    private $characterOffsets;

    public function __construct(?Subjectable $subjectable, bool $characterOffsets)
    {
        $this->subjectable = $subjectable;
        $this->characterOffsets = $characterOffsets;
    }

    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        foreach ($groups as &$group) {
            $group = \array_map(function (IRawMatchOffset $match) {
                $byteOffset = $match->byteOffset();
                if ($this->characterOffsets) {
                    return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $byteOffset);
                }
                return $byteOffset;
            }, $group);
        }
        return $groups;
    }
}
