<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use function array_key_exists;
use function array_keys;
use function array_map;
use function is_array;

class RawMatchOffset implements IRawMatchOffset, IRawMatchGroupable
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
        [$text, $offset] = $this->match[0];
        return $text;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->match);
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
        [$text, $offset] = $this->match[$nameOrIndex];
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    public function getGroupKeys(): array
    {
        return array_keys($this->match);
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        if (!array_key_exists($nameOrIndex, $this->match)) {
            return false;
        }
        $match = $this->match[$nameOrIndex];
        if (is_array($match)) {
            [$text, $offset] = $match;
            return $offset !== -1;
        }
        return false;
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->match[$nameOrIndex];
    }

    /**
     * @return (string|null)[]
     */
    public function getGroupsTexts(): array
    {
        return array_map(function ($match) {
            if ($match === null) {
                return null;
            }
            if ($match === '') {
                return null;
            }
            if (is_array($match)) {
                [$text, $offset] = $match;
                if ($offset === -1) {
                    return null;
                }
                return $text;
            }
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }, $this->match);
    }

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array
    {
        // TODO write a test for $match==null
        return array_map(function (array $match) {
            [$text, $offset] = $match;
            return $offset;
        }, $this->match);
    }
}
