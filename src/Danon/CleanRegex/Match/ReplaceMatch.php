<?php
namespace Danon\CleanRegex\Match;

use Danon\CleanRegex\Exception\Preg\PatternMatchException;
use Danon\CleanRegex\Internal\Pattern;

class ReplaceMatch extends Match
{
    /** @var Pattern */
    private $pattern;

    public function __construct(string $subject, int $index, array $matches, Pattern $pattern)
    {
        parent::__construct($subject, $index, $matches);
        $this->pattern = $pattern;
    }

    public function all(): array
    {
        $this->matches = $this->analyzePattern();

        return array_map(function ($match) {
            list($value, $offset) = $match;
            return $value;
        }, $this->matches[self::WHOLE_MATCH]);
    }

    private function analyzePattern(): array
    {
        $matches = [];
        $result = preg_match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        if ($result === false) {
            throw new PatternMatchException();
        }
        return $matches;
    }

    public function offset(): int
    {
        list($value, $offset) = $this->matches[self::WHOLE_MATCH][$this->index];
        return $offset;
    }

    public function modifiedOffset(): int
    {
        return -2;
    }
}
