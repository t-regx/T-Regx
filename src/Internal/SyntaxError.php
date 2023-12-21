<?php
namespace Regex\Internal;

class SyntaxError
{
    private string $message;
    private string $pattern;
    private int $position;

    public function __construct(string $message, string $pattern, int $position)
    {
        $this->message = $message;
        $this->pattern = $pattern;
        $this->position = $position;
    }

    public function __toString(): string
    {
        return "$this->message, near position $this->position.\n\n" . $this->patternInQuotes();
    }

    private function patternInQuotes(): string
    {
        if (\strPos($this->pattern, "'") === false) {
            return "'$this->pattern'";
        }
        return '"' . $this->pattern . '"';
    }
}
