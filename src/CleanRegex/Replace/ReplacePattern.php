<?php
namespace CleanRegex\Replace;

use CleanRegex\Internal\Pattern;
use SafeRegex\ExceptionFactory;

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
        $result = @preg_replace($this->pattern->pattern, $replacement, $this->subject);
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_replace', $result);

        return $result ?: "";
    }

    public function callback(callable $callback): string
    {
        return (new ReplacePatternCallbackInvoker($this->pattern, $this->subject))->invoke($callback);
    }
}
