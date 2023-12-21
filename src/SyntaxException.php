<?php
namespace Regex;

final class SyntaxException extends RegexException
{
    public function __construct(string $message, int $syntaxErrorPosition)
    {
        parent::__construct("$message, near position $syntaxErrorPosition.");
    }
}
