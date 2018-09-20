<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use CleanRegex\Internal\GroupNameValidator;
use CleanRegex\Internal\Pattern;
use CleanRegex\Internal\PatternLimit;
use CleanRegex\Match\Details\Match;
use CleanRegex\Match\Groups\Strategy\GroupVerifier;
use CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;
use InvalidArgumentException;
use SafeRegex\preg;

class MatchPattern implements PatternLimit
{
    private const GROUP_WHOLE_MATCH = 0;
    private const FIRST_MATCH = 0;

    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Pattern $pattern, string $subject, GroupVerifier $groupVerifier = null)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->groupVerifier = $groupVerifier ?? new MatchAllGroupVerifier();
    }

    public function all(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return $matches[self::GROUP_WHOLE_MATCH];
    }

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return array_slice($this->all(), 0, $limit);
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        (new GroupNameValidator($nameOrIndex))->validate();

        return new GroupLimit(
            function () use ($nameOrIndex) {
                $matches = [];
                preg::match_all($this->pattern->pattern, $this->subject, $matches);
                if (array_key_exists($nameOrIndex, $matches)) {
                    return $matches[$nameOrIndex];
                }
                throw new NonexistentGroupException($nameOrIndex);
            },
            function () use ($nameOrIndex) {
                $matches = [];
                preg::match($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
                if (array_key_exists($nameOrIndex, $matches)) {
                    return $matches[$nameOrIndex];
                }
                if ($this->groupExists($nameOrIndex)) {
                    return null;
                }
                throw new NonexistentGroupException($nameOrIndex);
            });
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL;
        }
        return 0;
    }

    /**
     * @param string|int
     * @return bool
     */
    private function groupExists($nameOrIndex): bool
    {
        return $this->groupVerifier->groupExists($this->pattern->pattern, $nameOrIndex);
    }

    public function iterate(callable $callback): void
    {
        foreach ($this->getMatchObjects() as $object) {
            $callback($object);
        }
    }

    public function map(callable $callback): array
    {
        $results = [];
        foreach ($this->getMatchObjects() as $object) {
            $results[] = $callback($object);
        }
        return $results;
    }

    /**
     * @param callable|null $callback
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $callback = null)
    {
        $matches = $this->performMatchAll();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            throw SubjectNotMatchedException::forFirst($this->subject);
        }
        if ($callback !== null) {
            return $callback(new Match($this->subject, self::FIRST_MATCH, $matches));
        }
        list($value, $offset) = $matches[self::GROUP_WHOLE_MATCH][self::FIRST_MATCH];
        return $value;
    }

    /**
     * @return Match[]
     */
    private function getMatchObjects(): array
    {
        return $this->constructMatchObjects($this->performMatchAll());
    }

    private function performMatchAll(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }

    /**
     * @param array $matches
     * @return Match[]
     */
    private function constructMatchObjects(array $matches): array
    {
        $matchObjects = [];
        foreach ($matches[self::GROUP_WHOLE_MATCH] as $index => $match) {
            $matchObjects[] = new Match($this->subject, $index, $matches);
        }
        return $matchObjects;
    }

    public function matches(): bool
    {
        return preg::match($this->pattern->pattern, $this->subject) === 1;
    }

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }
}
