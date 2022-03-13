<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\CompositeReplacePattern;
use TRegx\CleanRegex\Replace\FocusReplacePattern;
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

    public function by(): ByReplacePattern
    {
        return $this->all()->by();
    }

    public function counting(callable $countReceiver): CompositeReplacePattern
    {
        return $this->all()->counting($countReceiver);
    }

    /**
     * @deprecated
     */
    public function focus($nameOrIndex): FocusReplacePattern
    {
        return $this->all()->focus($nameOrIndex);
    }
}
