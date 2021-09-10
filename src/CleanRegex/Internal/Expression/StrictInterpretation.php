<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

/**
 * "Strict interpretation" means an {@see Expression} only takes a particular form:
 * definition(delimiter, word, delimiter, flags; undeveloped)
 */
trait StrictInterpretation
{
    abstract protected function word(): Word;

    abstract protected function delimiter(): Delimiter;

    abstract protected function flags(): Flags;

    abstract protected function undevelopedInput(): string;

    public function definition(): Definition
    {
        $word = $this->word();
        return new Definition($this->delimiter()->delimited($word, $this->flags()), $this->undevelopedInput());
    }
}
