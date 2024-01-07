<?php
namespace Regex\Internal;

use Regex\SyntaxException;

class DelimitedExpression
{
    public string $delimited;

    public function __construct(string $pattern)
    {
        $this->delimited = "/$pattern/";
        $parsed = new ParsedPattern($this->delimited);
        if ($parsed->syntaxErrorMessage) {
            throw new SyntaxException($this->exceptionMessage($parsed->syntaxErrorMessage));
        }
    }

    private function exceptionMessage(string $message): string
    {
        return \ucFirst(\subStr($message, \strLen('Compilation failed: '))) . '.';
    }
}
