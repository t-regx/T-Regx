<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;

class RawMatches implements IRawMatches, IRawWithGroups
{
    private const GROUP_WHOLE_MATCH = 0;

    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        throw new InternalCleanRegexException();
    }

    public function getAll(): array
    {
        return $this->getGroupTexts(self::GROUP_WHOLE_MATCH);
    }

    /**
     * @param string|int $nameOrIndex
     * @return string[]
     */
    public function getGroupTexts($nameOrIndex): array
    {
        return $this->matches[$nameOrIndex];
    }

    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->matches);
    }

    /**
     * @return (string|int)[]
     */
    public function getGroupKeys(): array
    {
        return array_keys($this->matches);
    }
}
