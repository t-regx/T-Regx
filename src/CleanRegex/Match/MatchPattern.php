<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Internal\GroupNameValidator;
use CleanRegex\Internal\Pattern;
use CleanRegex\Match\Details\Match;
use SafeRegex\preg;

class MatchPattern
{
    private const GROUP_WHOLE_MATCH = 0;
    private const FIRST_MATCH = 0;

    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function all(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return $matches[self::GROUP_WHOLE_MATCH];
    }

    /**
     * @param string|int $nameOrIndex
     * @return array
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex)
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        if (array_key_exists($nameOrIndex, $matches)) {
            return $matches[$nameOrIndex];
        }
        throw new NonexistentGroupException($nameOrIndex);
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
     * @return string|null|mixed
     */
    public function first(callable $callback = null)
    {
        $matches = $this->performMatchAll();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            return null;
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
