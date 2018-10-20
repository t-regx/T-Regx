<?php
namespace TRegx\CleanRegex\Internal\Model;

interface RawMatchesInterface
{
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
