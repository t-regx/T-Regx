<?php
namespace TRegx\CleanRegex\Internal;

class Flags
{
    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->flags = $flags;
    }

    public static function empty(): Flags
    {
        return new Flags('');
    }

    public static function from(?string $flagsString): Flags
    {
        return new Flags($flagsString ?? '');
    }

    public function __toString(): string
    {
        return \join('', \array_unique(\str_split($this->flags)));
    }
}
