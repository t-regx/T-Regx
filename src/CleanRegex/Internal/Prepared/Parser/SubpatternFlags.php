<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Flags;

class SubpatternFlags
{
    /** @var bool */
    private $extended;
    /** @var false */
    private $noAutoCapture;

    private function __construct(bool $extended, bool $noAutoCapture)
    {
        $this->extended = $extended;
        $this->noAutoCapture = $noAutoCapture;
    }

    public static function from(Flags $flags): SubpatternFlags
    {
        return new self($flags->isExtended(), $flags->noAutoCapture());
    }

    public static function empty(): SubpatternFlags
    {
        return new self(false, false);
    }

    public function parsed(string $string): SubpatternFlags
    {
        $flagString = new FlagString($string);
        return $this->nextSubpattern(
            $flagString->extendedOrFallback($this->extended),
            $flagString->noAutoCaptureOrFallback($this->noAutoCapture));
    }

    private function nextSubpattern(bool $extended, bool $noAutoCapture): SubpatternFlags
    {
        if ($extended === $this->extended && $noAutoCapture === $this->noAutoCapture) {
            return $this;
        }
        return new SubpatternFlags($extended, $noAutoCapture);
    }

    public function isExtended(): bool
    {
        return $this->extended;
    }

    public function noAutoCapture(): bool
    {
        return $this->noAutoCapture;
    }
}
