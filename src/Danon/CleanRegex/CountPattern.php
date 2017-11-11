<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use Danon\CleanRegex\Internal\Pattern;
use Danon\SafeRegex\preg;

class CountPattern
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

    public function count(): int
    {
        $result = preg::match_all($this->pattern->pattern, $this->string, $matches);
        if ($result !== count($matches[0])) {
            throw new InternalCleanRegexException();
        }
        return $result;
    }
}
