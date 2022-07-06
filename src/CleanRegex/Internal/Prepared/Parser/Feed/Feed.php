<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class Feed
{
    /** @var ShiftString */
    private $shiftString;
    /** @var Letter */
    private $letter;
    /** @var CharacterClassCondition */
    private $characterClass;
    /** @var ConstantStrings */
    private $constantStrings;

    public function __construct(string $string)
    {
        $this->shiftString = new ShiftString($string);
        $this->letter = new Letter($this->shiftString);
        $this->characterClass = new CharacterClassCondition($this->shiftString);
        $this->constantStrings = new ConstantStrings($this->shiftString);
    }

    public function letter(): Letter
    {
        return $this->letter;
    }

    public function string(string $string): ConstantString
    {
        return $this->constantStrings->string($string);
    }

    public function oneOf(array $values): OneOf
    {
        return new OneOf($this->shiftString, $values);
    }

    public function characterClass(): CharacterClassCondition
    {
        return $this->characterClass;
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
