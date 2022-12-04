<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

class FlagString
{
    /** @var string */
    private $flagString;
    /** @var int|false */
    private $extendedFlagPosition;
    /** @var int|false */
    private $noAutoCaptureFlagPosition;
    /** @var bool */
    private $resetsFlags;
    /** @var int */
    private $amountOfConstructionFlags;

    public function __construct(string $flagString)
    {
        $this->flagString = $flagString;
        $this->extendedFlagPosition = \strRPos($flagString, 'x');
        $this->noAutoCaptureFlagPosition = \strRPos($flagString, 'n');
        $this->resetsFlags = $this->resetsFlags();
        $this->amountOfConstructionFlags = $this->amountOfConstructionFlags();
    }

    private function resetsFlags(): bool
    {
        if ($this->flagString === '') {
            return false;
        }
        return $this->flagString[0] === '^';
    }

    private function amountOfConstructionFlags(): int
    {
        $position = \strPos($this->flagString, '-');
        if ($position === false) {
            return \strLen($this->flagString);
        }
        return $position;
    }

    public function extendedOrFallback(bool $previousState): bool
    {
        if ($this->resetsFlags) {
            return $this->extendedFlagPosition !== false;
        }
        return $this->constructiveFlag('x', $previousState);
    }

    public function noAutoCaptureOrFallback(bool $previousState): bool
    {
        if ($this->resetsFlags) {
            return $this->noAutoCaptureFlagPosition !== false;
        }
        return $this->constructiveFlag('n', $previousState);
    }

    private function constructiveFlag(string $flag, bool $previousState): bool
    {
        $lastPosition = \strRPos($this->flagString, $flag);
        if ($lastPosition === false) {
            return $previousState;
        }
        return $lastPosition < $this->amountOfConstructionFlags;
    }
}
