<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;
use SafeRegex\Guard\GuardedExecution;

class ValidPattern
{
    /** @var Pattern */
    private $pattern;

    public function __construct(Pattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function isValid(): bool
    {
        $hadError = GuardedExecution::silenced('preg_match', function () {
            return @preg_match($this->pattern->originalPattern, null);
        });

        return $hadError === false;
    }
}
