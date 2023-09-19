<?php
namespace Regex\Internal;

use Regex\ModifierException;

class Modifiers
{
    public bool $autoCapture;
    private string $modifiers;

    public function __construct(string $modifiers)
    {
        if (\trim($modifiers, 'miuJxsnUAS') !== '') {
            throw new ModifierException($modifiers);
        }
        $this->modifiers = \str_replace('n', '', \count_chars("DX$modifiers", 3));
        $this->autoCapture = \strPos($modifiers, 'n') !== false;
    }

    public function __toString(): string
    {
        return $this->modifiers;
    }
}
