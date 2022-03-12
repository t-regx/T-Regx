<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class ConstantStrings
{
    /** @var ShiftString */
    private $shiftString;
    /** @var ConstantString[] */
    private $cachedForPerformance = [];

    public function __construct(ShiftString $shiftString)
    {
        $this->shiftString = $shiftString;
    }

    public function string(string $string): ConstantString
    {
        if (!\array_key_exists($string, $this->cachedForPerformance)) {
            $this->cachedForPerformance[$string] = new ConstantString($this->shiftString, $string);
        }
        return $this->cachedForPerformance[$string];
    }
}
