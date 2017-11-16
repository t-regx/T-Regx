<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;
use SafeRegex\ExceptionFactory;

class SplitPattern
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

    public function split(): array
    {
        $result = @preg_split($this->pattern->pattern, $this->subject);
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_split', $result);

        return $result;
    }

    public function separate(): array
    {
        $result = @preg_split($this->pattern->pattern, $this->subject, -1, PREG_SPLIT_DELIM_CAPTURE);
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_split', $result);

        return $result;
    }
}
