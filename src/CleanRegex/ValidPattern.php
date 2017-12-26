<?php
namespace CleanRegex;

use CleanRegex\Exception\CleanRegex\ArgumentNotAllowedException;
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

    public static function matchableArgument($argument): string
    {
        if (is_string($argument)) {
            return $argument;
        }

        if (is_int($argument)) {
            return "$argument";
        }

        if (is_callable([$argument, '__toString'])) {
            return (string)$argument;
        }

        throw new ArgumentNotAllowedException('Argument should be a string, an integer or implement __toString() method!');
    }
}
