<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\SafeRegex\Internal\Tuple;

/**
 * @deprecated
 */
class RawMatchOffset
{
    /** @var array[] */
    private $match;

    public function __construct(array $match)
    {
        $this->match = $match;
    }

    public function matched(): bool
    {
        return !empty($this->match);
    }

    public function getText(): string
    {
        return Tuple::first($this->match[0]);
    }

    public function hasGroup(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->match);
    }

    public function getGroup($nameOrIndex): ?string
    {
        [$text, $offset] = $this->match[$nameOrIndex];
        if ($offset === -1) {
            return null;
        }
        return $text;
    }

    public function byteOffset(): int
    {
        return $this->getGroupByteOffset(0);
    }

    public function getGroupByteOffset($nameOrIndex): ?int
    {
        $offset = Tuple::second($this->match[$nameOrIndex]);
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    public function getGroupKeys(): array
    {
        return \array_keys($this->match);
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        if (!\array_key_exists($nameOrIndex, $this->match)) {
            return false;
        }
        $match = $this->match[$nameOrIndex];
        if (\is_array($match)) {
            return Tuple::second($match) !== -1;
        }
        return false;
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->match[$nameOrIndex];
    }
}
