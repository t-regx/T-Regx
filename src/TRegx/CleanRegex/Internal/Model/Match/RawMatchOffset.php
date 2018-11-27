<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Match;
use function array_key_exists;

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
        return $this->getMatch();
    }

    public function getMatch(): string
    {
        list($text, $offset) = $this->match[0];
        return $text;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->match);
    }

    public function getGroup($nameOrIndex): ?string
    {
        list($text, $offset) = $this->match[$nameOrIndex];
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
        list($text, $offset) = $this->match[$nameOrIndex];
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    /**
     * @return (string|int)[]
     */
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
            list($text, $offset) = $match;
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
            if (is_array($match)) {
                list($text, $offset) = $match;
                return $text;
            }
            throw new InternalCleanRegexException();
        }, $this->match);
    }

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array
    {
        // TODO write a test for $match==null
        return array_map(function (array $match) {
            list($text, $offset) = $match;
            return $offset;
        }, $this->match);
    }

    public function getMatchObject(MatchAllFactory $allFactory, Subjectable $subjectable): Match
    {
        return new Match($subjectable, 0, $this, $allFactory);
    }
}
