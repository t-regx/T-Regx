<?php
namespace TRegx\CleanRegex\Internal;

class Definition
{
    /** @var string */
    public $pattern;
    /** @var string */
    public $undevelopedInput;

    public function __construct(string $pattern, string $undevelopedInput)
    {
        $this->pattern = $pattern;
        $this->undevelopedInput = $undevelopedInput;
    }
}
