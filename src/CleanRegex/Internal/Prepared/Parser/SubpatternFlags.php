<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Flags;

class SubpatternFlags
{
    /** @var bool */
    private $extended;

    private function __construct(bool $extended)
    {
        $this->extended = $extended;
    }

    public static function from(Flags $flags): SubpatternFlags
    {
        return new self($flags->isExtended());
    }

    public static function empty(): SubpatternFlags
    {
        return new self(false);
    }

    public function parsed(string $string): SubpatternFlags
    {
        $flagString = new FlagString($string);
        if ($flagString->changesExtended()) {
            return new SubpatternFlags($flagString->isExtended());
        }
        return $this;
    }

    public function isExtended(): bool
    {
        return $this->extended;
    }
}
