<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFactory;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;
use InvalidArgumentException;
use TRegx\SafeRegex\preg;

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

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return array_slice($this->all(), 0, $limit);
    }

    public function matches(): bool
    {
        return preg::match($this->pattern->pattern, $this->subject) === 1;
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
     * @param callable $callback
     * @return Optional
     */
    public function forFirst(callable $callback): Optional
    {
        $matches = $this->performMatchAll();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            return new NotMatchedOptional($matches, $this->subject);
        }

        $result = $callback(new Match($this->subject, self::FIRST_MATCH, $matches));
        return new MatchedOptional($result);
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return (new GroupLimitFactory($this->pattern, $this->subject, $nameOrIndex))->create();
    }

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }

    /**
     * @return Match[]
     */
    private function getMatchObjects(): array
    {
        return $this->constructMatchObjects($this->performMatchAll());
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

    private function performMatchAll(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        return $matches;
    }
}
