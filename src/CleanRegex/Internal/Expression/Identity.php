<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\IdentityPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;

class Identity implements Expression
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function predefinition(): Predefinition
    {
        return new IdentityPredefinition($this->definition);
    }
}
