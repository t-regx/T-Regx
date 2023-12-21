<?php
namespace Regex\Internal;

use Regex\DelimiterException;
use Regex\SyntaxException;

class Delimiter
{
    /** @var string[] */
    private static array $candidates = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1", "\2"];
    private string $delimiter;

    public function __construct(string $pattern)
    {
        if ($this->hasTrailingBackslash($pattern)) {
            throw new SyntaxException('Trailing backslash in regular expression',
                \strLen($pattern) - 1);
        }
        $this->delimiter = $this->delimiter($pattern);
    }

    private function hasTrailingBackslash(string $pattern): bool
    {
        return \subStr(\str_replace('\\\\', '', $pattern), -1) === "\\";
    }

    private function delimiter(string $pattern): string
    {
        foreach (self::$candidates as $candidate) {
            if (\strPos($pattern, $candidate) === false) {
                return $candidate;
            }
        }
        throw new DelimiterException();
    }

    public function __toString(): string
    {
        return $this->delimiter;
    }
}
