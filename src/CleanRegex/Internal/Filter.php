<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\SafeRegex\preg;

class Filter
{
    /** @var Predefinition */
    private $predefinition;

    public function __construct(Predefinition $predefinition)
    {
        $this->predefinition = $predefinition;
    }

    public function filtered(array $subjects): array
    {
        foreach ($subjects as $value) {
            if (!\is_string($value)) {
                throw InvalidArgument::typeGiven("Expected an array of elements of type 'string' to be filtered", new ValueType($value));
            }
        }
        return \array_values(preg::grep($this->predefinition->definition()->pattern, $subjects));
    }
}
