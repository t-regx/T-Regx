<?php
namespace TRegx\CleanRegex\Analyze\Simplify;

class UnnecessaryGroupEscapes
{
    /** @var LiteralLetters */
    private $literalTokens;

    /** @var boolean */
    private $escapeClosingGroup;

    public function __construct(LiteralLetters $literalTokens, bool $escapeClosingGroup)
    {
        $this->literalTokens = $literalTokens;
        $this->escapeClosingGroup = $escapeClosingGroup;
    }

    public function remove(array $tokens): array
    {
        return array_map(function (string $token) {
            if ($token[0] !== '\\') {
                return $token;
            }
            return $this->addQuoteIfRequired($token[1]);
        }, $tokens);
    }

    public function addQuoteIfRequired(string $character): string
    {
        if ($this->isEscapeRequired($character)) {
            return "\\" . $character;
        }
        return $character;
    }

    private function isEscapeRequired($character): bool
    {
        return $this->isEscapeCharacter($character) || $this->literalTokens->isLetterNotLiteral($character);
    }

    private function isEscapeCharacter(string $character): bool
    {
        return in_array($character, $this->escapeCharacters());
    }

    private function escapeCharacters(): array
    {
        if ($this->escapeClosingGroup) {
            return [']', '\\'];
        }
        return ['\\'];
    }
}
