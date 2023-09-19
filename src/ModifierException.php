<?php
namespace Regex;

final class ModifierException extends RegexException
{
    public function __construct(string $modifiers)
    {
        $disqualified = $this->disqualified($modifiers);
        if ($disqualified) {
            parent::__construct("Supplied one or more unexpected modifiers: '$modifiers', modifier '$disqualified' is already applied.");
        } else {
            parent::__construct("Supplied one or more unexpected modifiers: '$modifiers'.");
        }
    }

    private function disqualified(string $modifiers): string
    {
        if (\strPos($modifiers, 'D') !== false) {
            return 'D';
        }
        if (\strPos($modifiers, 'X') !== false) {
            return 'X';
        }
        return '';
    }
}
