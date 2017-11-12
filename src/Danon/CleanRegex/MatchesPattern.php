<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Exception\Preg\PatternMatchesException;
use Danon\CleanRegex\Internal\Pattern;

class MatchesPattern
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

    public function matches(): bool
    {
        $argument = ValidPattern::matchableArgument($this->subject);

        $result = @preg_match($this->pattern->pattern, $argument);
        if ($result === false) {
            throw new PatternMatchesException(preg_last_error());
        }

        return $result === 1;
    }
}
