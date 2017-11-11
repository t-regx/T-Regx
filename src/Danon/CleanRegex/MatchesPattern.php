<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Exception\Preg\PatternMatchesException;
use Danon\CleanRegex\Internal\Pattern;

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
            throw new PatternMatchesException(preg_last_error());
        }

        return $result === 1;
    }
}
