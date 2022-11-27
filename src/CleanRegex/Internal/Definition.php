<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\SafeRegex\Internal\Guard\GuardedExecution;

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

    public function containsNullByte(): bool
    {
        return \strPos($this->pattern, "\0") !== false;
    }

    public function valid(): bool
    {
        return !GuardedExecution::silenced('preg_match', function () {
            return @\preg_match($this->pattern, '');
        });
    }
}
