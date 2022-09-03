<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Replace\LimitlessReplacePattern;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;

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

    public function counting(callable $countReceiver): SpecificReplacePattern
    {
        return $this->all()->counting($countReceiver);
    }
}
