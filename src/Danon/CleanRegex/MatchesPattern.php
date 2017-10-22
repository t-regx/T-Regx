<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Exception\PatternMatchesException;

class MatchesPattern
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

    public function matches(): bool
    {
        $result = @preg_match($this->pattern->pattern, $this->string);
        if ($result === false) {
            $lastError = preg_last_error();
            throw new PatternMatchesException($lastError);
        }

        return $result === 1;
    }
}
