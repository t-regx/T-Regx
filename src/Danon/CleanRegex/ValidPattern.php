<?php
namespace Danon\CleanRegex;

class ValidPattern
{
    /** @var Pattern */
    private $pattern;

    public function __construct(Pattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function isValid()
    {
        $result = @preg_match($this->pattern->pattern, null);
        return $result !== false;
    }
}
