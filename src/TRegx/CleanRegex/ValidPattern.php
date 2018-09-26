<?php
namespace TRegx\CleanRegex;

use TRegx\SafeRegex\Guard\GuardedExecution;
use function preg_match;

class ValidPattern
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function isValid(): bool
    {
        $hadError = GuardedExecution::silenced('preg_match', function () {
            return @preg_match($this->pattern, null);
        });

        return $hadError === false;
    }
}
