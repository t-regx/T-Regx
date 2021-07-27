<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface UsedInCompositeGroups
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
