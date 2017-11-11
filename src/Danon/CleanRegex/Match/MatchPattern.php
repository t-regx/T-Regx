<?php
namespace Danon\CleanRegex\Match;

use Danon\CleanRegex\Exception\Preg\PatternMatchException;
use Danon\CleanRegex\Internal\Pattern;

class MatchPattern
{
    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
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

    public function first(callable $callback = null): ?string
    {
        $matches = $this->performMatchOne();
        if (empty($matches)) return null;

        if ($callback !== null) {
            call_user_func($callback, new Match($this->subject, 0, [$matches]));
        }

        list($value, $offset) = $matches[0];
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
        $result = preg_match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        if ($result === false) {
            throw new PatternMatchException();
        }
        return $matches;
    }

    private function performMatchOne(): array
    {
        $matches = [];
        $result = preg_match($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        if ($result === false) {
            throw new PatternMatchException();
        }
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

    public function matches()
    {
        $result = preg_match($this->pattern->pattern, $this->subject);
        if ($result === false) {
            throw new PatternMatchException();
        }
        return $result === 1;
    }

    public function count(): int
    {
        $result = preg_match_all($this->pattern->pattern, $this->subject);
        if ($result === false) {
            throw new PatternMatchException();
        }
        return $result;
    }
}
