<?php
namespace Regex;

final class SyntaxException extends RegexException
{
    public string $syntaxErrorPattern;
    public int $syntaxErrorByteOffset;

    public function __construct(string $message, string $pattern, int $syntaxErrorPosition)
    {
        parent::__construct("$message, near position $syntaxErrorPosition.");
        $this->syntaxErrorPattern = $pattern;
        $this->syntaxErrorByteOffset = $syntaxErrorPosition;
    }
}
