<?php
namespace Danon\CleanRegex\Match;

use Danon\CleanRegex\Exception\PatternMatchException;
use Danon\CleanRegex\Pattern;

class MatchPattern
{
    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $string;

    public function __construct(Pattern $pattern, string $string)
    {
        $this->pattern = $pattern;
        $this->string = $string;
    }

    public function iterate(callable $callback): void
    {
        $matches = [];
        $result = preg_match($this->pattern->pattern, $this->string, $matches, PREG_OFFSET_CAPTURE);
        if ($result === false) {
            throw new PatternMatchException();
        }
        list($first, $firstOffset) = array_shift($matches);
        foreach ($matches as $index => $match) {
            list($substring, $offset) = $match;
            call_user_func($callback, new Match($first, $index, $substring, $offset, $matches));
        }
    }

    public function matches()
    {
        $result = preg_match($this->pattern->pattern, $this->string);
        if ($result === false) {
            throw new PatternMatchException();
        }
        return $result === 1;
    }
}
