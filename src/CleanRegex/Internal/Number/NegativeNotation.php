<?php
namespace TRegx\CleanRegex\Internal\Number;

class NegativeNotation implements Notation
{
    /** @var PositiveNotation */
    private $absoluteValue;
    /** @var NumberLowerBound */
    private $lowerBound;

    public function __construct(PositiveNotation $absoluteValue)
    {
        $this->absoluteValue = $absoluteValue;
        $this->lowerBound = new NumberLowerBound();
    }

    public function integer(Base $base): int
    {
        if ($this->lowerBound->lowerBound($base) === \strtolower($this->absoluteValue)) {
            return $this->lowerBound->minimalValue();
        }
        return -$this->absoluteValue->integer($base);
    }
}
