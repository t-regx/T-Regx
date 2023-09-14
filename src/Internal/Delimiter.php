<?php
namespace Regex\Internal;

use Regex\DelimiterException;

class Delimiter
{
    /** @var string[] */
    private static array $candidates = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1", "\2"];
    private string $delimiter;

    public function __construct(string $pattern)
    {
        $this->delimiter = $this->delimiter($pattern);
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
