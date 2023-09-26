<?php
namespace Regex\Internal;

class SyntaxError
{
    private string $message;
    private BinaryString $pattern;
    private int $position;

    public function __construct(string $message, string $pattern, int $position)
    {
        $this->message = $message;
        $this->pattern = new BinaryString($pattern);
        $this->position = $position;
    }

    public function __toString(): string
    {
        return "$this->message, near position $this->position.\n\n"
            . $this->patternWithCaret()
            . $this->containsControl();
    }

    private function patternWithCaret(): string
    {
        $pattern = $this->patternInQuotes();
        if ($this->pattern->multiline) {
            return "$pattern\n";
        }
        return "$pattern\n" . $this->caret();
    }

    private function patternInQuotes(): string
    {
        if (\strPos($this->pattern, "'") === false) {
            return "'$this->pattern'";
        }
        return '"' . $this->pattern . '"';
    }

    private function caret(): string
    {
        return ' ' . \str_repeat(' ', $this->unicodePosition()) . '^';
    }

    private function unicodePosition(): int
    {
        return \mb_strLen(\subStr($this->pattern, 0, $this->position), 'UTF-8');
    }

    private function containsControl(): string
    {
        if ($this->pattern->containsControl) {
            return "\n(contains non-printable characters)";
        }
        return '';
    }
}
