<?php
namespace Danon\CleanRegex\Replace;

use Danon\CleanRegex\Exception\Preg\PatternReplaceException;
use Danon\CleanRegex\Internal\Pattern;

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
        $result = preg_replace_callback($this->pattern->pattern, $callback, $this->subject);
        if ($result === null) {
            throw new PatternReplaceException();
        }
        return $result ?: "";
    }
}
