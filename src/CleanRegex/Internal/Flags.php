<?php
namespace TRegx\CleanRegex\Internal;

class Flags
{
    /** @var string */
    private $modifiers;

    public function __construct(string $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    public static function empty(): Flags
    {
        return new Flags('');
    }

    public static function from(?string $modifiersString): Flags
    {
        return new Flags($modifiersString ?? '');
    }

    public function isExtended(): bool
    {
        return $this->contains('x');
    }

    public function noAutoCapture(): bool
    {
        return $this->contains('n');
    }

    private function contains(string $modifier): bool
    {
        return \strPos($this->modifiers, $modifier) !== false;
    }

    public function toPcreModifiers(): string
    {
        return $this->modifiers;
    }
}
