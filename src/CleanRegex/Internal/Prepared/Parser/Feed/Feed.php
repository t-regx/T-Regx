<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class Feed
{
    /** @var ShiftString */
    private $shiftString;

    public function __construct(string $string)
    {
        $this->shiftString = new ShiftString($string);
    }

    public function letter(): Letter
    {
        return new Letter($this->shiftString);
    }

    public function string(string $string): ConstantString
    {
        return new ConstantString($this->shiftString, $string);
    }

    public function matchedString(string $regex, int $groups): MatchedString
    {
        return new MatchedString($this->shiftString, $regex, $groups);
    }

    public function empty(): bool
    {
        return $this->shiftString->empty();
    }
}
