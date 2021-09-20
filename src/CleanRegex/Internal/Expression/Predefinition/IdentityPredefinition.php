<?php
namespace TRegx\CleanRegex\Internal\Expression\Predefinition;

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
        return $this->definition;
    }

    public function valid(): bool
    {
        return $this->definition->valid();
    }
}
