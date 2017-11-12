<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Internal\Pattern;

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
        return preg_split($this->pattern->pattern, $this->subject);
    }

    public function separate(): array
    {
        return preg_split($this->pattern->pattern, $this->subject, PREG_SPLIT_DELIM_CAPTURE);
    }
}
