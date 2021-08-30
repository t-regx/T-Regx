<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;

class Identity implements Expression
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
}
