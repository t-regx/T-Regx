<?php
namespace TRegx\CleanRegex\Match\Details;

use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_values;
use function count;

class NotMatched implements Details
{
    /** @var array */
    private $matches;
    /** @var string */
    private $subject;

    public function __construct(array $matches, string $subject)
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
        return array_values(array_filter(array_keys($this->matches), function ($key) {
            return is_string($key);
        }));
    }

    public function groupsCount(): int
    {
        $indexedGroups = array_filter(array_keys($this->matches), 'is_int');
        return count($indexedGroups) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->matches);
    }
}
