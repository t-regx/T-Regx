<?php
namespace CleanRegex\Replace;

use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class ReplacePattern
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

    public function with(string $replacement): string
    {
        return preg::replace($this->pattern->pattern, $replacement, $this->subject);
    }

    public function callback(callable $callback): string
    {
        return (new ReplacePatternCallbackInvoker($this->pattern, $this->subject))->invoke($callback);
    }
}
