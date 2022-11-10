<?php
namespace TRegx\CleanRegex\Internal\Numeral;

class NegativeNotation implements Notation
{
    /** @var PositiveNotation */
    private $absoluteValue;
    /** @var NumeralLowerBound */
    private $lowerBound;

    public function __construct(PositiveNotation $absoluteValue)
    {
        $this->absoluteValue = $absoluteValue;
        $this->lowerBound = new NumeralLowerBound();
    }

    public function integer(Base $base): int
    {
        if ($this->lowerBound->lowerBound($base) === \strToLower($this->absoluteValue)) {
            return $this->lowerBound->minimalValue();
        }
        return -$this->absoluteValue->integer($base);
    }
}
