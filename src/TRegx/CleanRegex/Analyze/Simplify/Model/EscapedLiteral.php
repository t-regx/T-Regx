<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Model;

use TRegx\CleanRegex\Analyze\Simplify\LiteralLetters;

class EscapedLiteral extends Model
{
    /** @var string */
    private $escaped;

    /** @var string[] */
    const CHARACTER_LITERALS = ['"', "'", '\]', '#', ' '];

    /** @var LiteralLetters */
    private $literalTokens;

    public function __construct(string $literal)
    {
        $this->escaped = $literal;
        $this->literalTokens = new LiteralLetters();
    }

    public function getContent(): string
    {
        $unescaped = $this->escaped[1];
        if ($this->isCharacterLiteral($unescaped) || $this->isLetterLiteral($unescaped)) {
            return $unescaped;
        }
        return $this->escaped;
    }

    public function getLiteralForCharacterGroup(): string
    {
        if ($this->escaped[1] === ']') {
            return '\\]';
        }
        return $this->escaped[1];
    }

    private function isCharacterLiteral(string $unescaped): bool
    {
        return in_array($unescaped, self::CHARACTER_LITERALS);
    }

    private function isLetterLiteral($unescaped): bool
    {
        return ctype_alpha($unescaped) && $this->literalTokens->isLiteral($unescaped);
    }

    public function isSingleToken(): bool
    {
        return true;
    }
}
