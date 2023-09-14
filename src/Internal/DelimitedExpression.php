<?php
namespace Regex\Internal;

use Regex\SyntaxException;

class DelimitedExpression
{
    public string $delimited;

    public function __construct(string $pattern, string $modifiers)
    {
        $delimiter = new Delimiter($pattern);
        $this->delimited = $delimiter . $pattern . $delimiter . 'DX' . $modifiers;
        $parsed = new ParsedPattern($this->delimited);
        if ($parsed->syntaxErrorMessage) {
            throw new SyntaxException($this->exceptionMessage($parsed->syntaxErrorMessage));
        }
    }

    private function exceptionMessage(string $message): string
    {
        return $this->duplicateNames($this->compilationFailed($message)) . '.';
    }

    private function compilationFailed(string $message): string
    {
        return \ucFirst(\str_replace('Compilation failed: ', '', $message));
    }

    private function duplicateNames(string $message): string
    {
        return \str_replace('name (PCRE2_DUPNAMES not set) at', 'name at', $message);
    }
}
