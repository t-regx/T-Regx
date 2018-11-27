<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

interface IRawMatches
{
    public function matched(): bool;

    /**
     * @return string[]
     */
    public function getAll(): array;

    /**
     * @param string|int $group
     * @return array (string|null)[]
     */
    public function getGroupTexts($group): array;
}
