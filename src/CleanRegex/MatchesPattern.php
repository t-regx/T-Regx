<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;
use SafeRegex\ExceptionFactory;

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
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_match', $result);

        return $result === 1;
    }
}
