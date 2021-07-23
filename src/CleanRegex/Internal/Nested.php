<?php
namespace TRegx\CleanRegex\Internal;

class Nested
{
    /** @var array */
    private $nestedValues;

    public function __construct(array $nested)
    {
        $this->nestedValues = $nested;
    }

    public function asArray(): array
    {
        foreach ($this->nestedValues as $nestedValue) {
            if (!\is_array($nestedValue)) {
                throw new NonNestedValueException(new ValueType($nestedValue));
            }
        }
        return $this->nestedValues;
    }
}
