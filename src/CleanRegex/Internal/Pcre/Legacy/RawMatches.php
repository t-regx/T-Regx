<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

/**
 * @deprecated
 */
class RawMatches implements GroupAware
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

    public function hasGroup(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->matches);
    }

    public function getGroupKeys(): array
    {
        return \array_keys($this->matches);
    }
}
