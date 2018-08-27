<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\Preg\PatternMatchException;
use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class MatchPattern
{
    private const WHOLE_MATCH = 0;

    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return array
     * @throws PatternMatchException
     */
    public function all(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches);

        return $matches[0];
    }

    public function iterate(callable $callback): void
    {
        foreach ($this->getMatchObjects() as $object) {
            call_user_func($callback, $object);
        }
    }

    public function map(callable $callback): array
    {
        $results = [];
        foreach ($this->getMatchObjects() as $object) {
            $results[] = call_user_func($callback, $object);
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
        if (empty($matches[0])) return null;

        if ($callback !== null) {
            return call_user_func($callback, new Match($this->subject, 0, $matches));
        }

        list($value, $offset) = $matches[self::WHOLE_MATCH][0];
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

        foreach ($matches[0] as $index => $match) {
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
