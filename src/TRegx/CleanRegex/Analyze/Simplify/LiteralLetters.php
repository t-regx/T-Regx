<?php
namespace TRegx\CleanRegex\Analyze\Simplify;

class LiteralLetters
{
    public function isLetterNotLiteral(string $character): bool
    {
        if (strlen($character) !== 1) {
            return false;
        }
        return ctype_alpha($character) && !$this->isLiteral($character);
    }

    public function isLiteral(string $character): bool
    {
        return in_array($character, $this->getLiteralTokens());
    }

    private function getLiteralTokens(): array
    {
        return [
            'F', 'I', 'J', 'M', 'O', 'T', 'Y',
            'i', 'j', 'm', 'q', 'y'
        ];
    }
}
