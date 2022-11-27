<?php
namespace TRegx\CleanRegex\Internal\Expression\Predefinition;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;

class IdentityPredefinition implements Predefinition
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function definition(): Definition
    {
        if ($this->definition->containsNullByte()) {
            throw new PatternMalformedPatternException('Pattern may not contain null-byte');
        }
        return $this->definition;
    }

    public function valid(): bool
    {
        return $this->definition->valid();
    }
}
