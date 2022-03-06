<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

class FlagString
{
    /** @var string */
    private $flagString;
    /** @var int|false */
    private $extendedFlagPosition;

    public function __construct(string $flagString)
    {
        $this->flagString = $flagString;
        $this->extendedFlagPosition = \strRPos($flagString, 'x');
    }

    public function changesExtended(): bool
    {
        return $this->extendedFlagPosition !== false;
    }

    public function isExtended(): bool
    {
        return $this->extendedFlagPosition < $this->amountOfConstructionFlags();
    }

    private function amountOfConstructionFlags(): int
    {
        $position = \strPos($this->flagString, '-');
        if ($position === false) {
            return \strLen($this->flagString);
        }
        return $position;
    }
}
