<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Replace\LimitlessReplacePattern;

trait ReplaceLimitHelpers
{
    public abstract function all(): LimitlessReplacePattern;

    public function with(string $replacement): string
    {
        return $this->all()->with($replacement);
    }

    public function withReferences(string $replacement): string
    {
        return $this->all()->withReferences($replacement);
    }

    public function callback(callable $callback): string
    {
        return $this->all()->callback($callback);
    }
}
