<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

/**
 * "Strict interpretation" means an {@see Expression} only takes a particular form:
 * definition(delimiter, quotable, delimiter, flags; undeveloped)
 */
trait StrictInterpretation
{
    abstract protected function quotable(): Quotable;

    abstract protected function delimiter(): Delimiter;

    abstract protected function flags(): Flags;

    abstract protected function undevelopedInput(): string;

    public function definition(): Definition
    {
        $quotable = $this->quotable();
        return new Definition($this->delimiter()->delimited($quotable, $this->flags()), $this->undevelopedInput());
    }
}
