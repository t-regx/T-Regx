<?php
namespace TRegx\CleanRegex\Match\Details;

use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_values;
use function count;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawWithGroups;

class NotMatched implements Details
{
    /** @var RawWithGroups */
    private $matches;
    /** @var string */
    private $subject;

    public function __construct(RawWithGroups $matches, string $subject)
    {
        $this->matches = $matches;
        $this->subject = $subject;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return array_values(array_filter($this->matches->getGroupKeys(), function ($key) {
            return is_string($key);
        }));
    }

    public function groupsCount(): int
    {
        $indexedGroups = array_filter($this->matches->getGroupKeys(), 'is_int');
        return count($indexedGroups) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->matches->hasGroup($nameOrIndex);
    }
}
