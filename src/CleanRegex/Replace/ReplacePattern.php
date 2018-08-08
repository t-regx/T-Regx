<?php
namespace CleanRegex\Replace;

use CleanRegex\Internal\Pattern;
use CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use SafeRegex\preg;

class ReplacePattern
{
    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $subject;

    /** @var int */
    private $limit;

    public function __construct(Pattern $pattern, string $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function with(string $replacement): string
    {
        return preg::replace($this->pattern->pattern, $replacement, $this->subject, $this->limit);
    }

    public function callback(callable $callback): string
    {
        return (new ReplacePatternCallbackInvoker($this->pattern, $this->subject, $this->limit))->invoke($callback);
    }
}
