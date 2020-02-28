<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;

class RawMatches implements IRawMatches, IRawWithGroups
{
    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function getTexts(): array
    {
        return $this->matches[0];
    }

    public function hasGroup($nameOrIndex): bool
    {
        return \array_key_exists($nameOrIndex, $this->matches);
    }

    /**
     * @return (string|int)[]
     */
    public function getGroupKeys(): array
    {
        return \array_keys($this->matches);
    }
}
