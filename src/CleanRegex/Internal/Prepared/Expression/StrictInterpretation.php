<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

trait StrictInterpretation
{
    abstract protected function quotable(): Quotable;

    abstract protected function delimiter(): Delimiter;

    abstract protected function flags(): Flags;

    abstract protected function undevelopedInput(): string;

    public function definition(): Definition
    {
        return new Definition($this->delimiter()->delimited($this->quotable(), $this->flags()), $this->undevelopedInput());
    }
}
