<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\SafeRegex\Internal\Guard\GuardedExecution;

class Definition
{
    /** @var string */
    public $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
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
