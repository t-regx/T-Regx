<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface GroupEntries
{
    /**
     * @see AbstractMatchGroups::texts
     */
    public function getGroupsTexts(): array;

    /**
     * @see AbstractMatchGroups::byteOffsets
     */
    public function getGroupsOffsets(): array;
}
