<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;

class Identity implements Expression
{
    /** @var Predefinition */
    private $predefinition;

    public function __construct(Predefinition $predefinition)
    {
        $this->predefinition = $predefinition;
    }

    public function predefinition(): Predefinition
    {
        return $this->predefinition;
    }
}
