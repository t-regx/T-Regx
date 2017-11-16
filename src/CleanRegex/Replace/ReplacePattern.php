<?php
namespace CleanRegex\Replace;

use CleanRegex\Exception\Preg\PatternReplaceException;
use CleanRegex\Internal\Pattern;

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
        $result = preg_replace($this->pattern->pattern, $replacement, $this->subject);
        if ($result === null) {
            throw new PatternReplaceException();
        }
        return $result ?: "";
    }

    public function callback(callable $callback): string
    {
        return (new ReplacePatternCallbackInvoker($this->pattern, $this->subject))->invoke($callback);
    }
}
