<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\Guard\GuardedExecution;

class ValidPattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
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
