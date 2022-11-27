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
        return \strPos($this->modifiers, 'x') !== false;
    }

    public function __toString(): string
    {
        return $this->modifiers;
    }
}
