<?php
namespace Danon\CleanRegex\Replace;

use Danon\CleanRegex\Exception\PatternReplaceException;
use Danon\CleanRegex\Pattern;

class ReplacePattern
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

    public function with(string $string): string
    {
        $result = preg_replace($this->pattern->pattern, $string, $this->string);
        if ($result === null) {
            throw new PatternReplaceException();
        }
        return $result ?: "";
    }

    public function callback(callable $callback): string
    {
        $result = preg_replace_callback($this->pattern->pattern, $callback, $this->string);
        if ($result === null) {
            throw new PatternReplaceException();
        }
        return $result ?: "";
    }
}
