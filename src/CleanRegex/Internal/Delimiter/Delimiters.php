<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

class Delimiters
{
    /** @var array */
    private static $validDelimiters = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1"];

    public static function getDelimiters(): array
    {
        return self::$validDelimiters;
    }

    public static function isValidDelimiter(string $character): bool
    {
        return \in_array($character, self::$validDelimiters, true);
    }
}
