<?php
namespace TRegx\CleanRegex\Internal\Expression\Predefinition;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class DelimiterPredefinition implements Predefinition
{
    /** @var Definition */
    private $definition;

    public function __construct(Delimiter $delimiter, Flags $flags, Word $word, string $undeveloped)
    {
        $this->definition = new Definition($delimiter->delimited($word, $flags), $undeveloped);
    }

    public function definition(): Definition
    {
        return $this->definition;
    }

    public function valid(): bool
    {
        return $this->definition->valid();
    }
}
