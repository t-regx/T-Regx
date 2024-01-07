<?php
namespace Regex;

use Regex\Internal\SyntaxError;
use Regex\Internal\UnicodeString;

final class SyntaxException extends PatternException
{
    public string $syntaxErrorPattern;
    public int $syntaxErrorByteOffset;

    public function __construct(string $message, string $pattern, int $syntaxErrorPosition)
    {
        parent::__construct(new SyntaxError($message, $pattern, $syntaxErrorPosition));
        $this->syntaxErrorPattern = $pattern;
        $this->syntaxErrorByteOffset = $syntaxErrorPosition;
    }

    public function syntaxErrorOffset(): int
    {
        $subject = new UnicodeString($this->syntaxErrorPattern);
        return $subject->offset($this->syntaxErrorByteOffset);
    }
}
