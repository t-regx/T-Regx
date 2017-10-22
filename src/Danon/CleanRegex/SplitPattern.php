<?php
namespace Danon\CleanRegex;

class SplitPattern
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

    public function split(): array
    {
        return preg_split($this->pattern->pattern, $this->string);
    }

    public function separate(): array
    {
        return preg_split($this->pattern->pattern, $this->string, PREG_SPLIT_DELIM_CAPTURE);
    }
}
