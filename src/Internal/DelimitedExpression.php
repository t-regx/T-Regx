<?php
namespace Regex\Internal;

use Regex\SyntaxException;

class DelimitedExpression
{
    public string $delimited;

    public function __construct(string $pattern, string $modifiers)
    {
        $this->delimited = "/$pattern/$modifiers";
        $parsed = new ParsedPattern($this->delimited);
        if ($parsed->syntaxErrorMessage) {
            throw new SyntaxException($this->compilationFailed($parsed->syntaxErrorMessage) . '.');
        }
    }

    private function compilationFailed(string $message): string
    {
        return \ucFirst(\str_replace('Compilation failed: ', '', $message));
    }
}
