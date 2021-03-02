<?php
namespace TRegx\CleanRegex\Internal;

class InvisibleCharacters
{
    public static function format(string $string): string
    {
        return \preg_replace_callback("#\\p{C}|\xc2\xa0#u", static function (array $matches) {
            return self::formatWord(...$matches);
        }, $string);
    }

    public static function formatWord(string $bytes): string
    {
        if ($bytes === "\xc2\xa0") {
            return '[NBSP\xc2\xa0]';
        }
        if ($bytes === "\x7f") {
            return '[DEL\x7f]';
        }
        if (\strlen($bytes) === 1) {
            return self::prettyCharacter($bytes);
        }
        return \join(\array_map([self::class, 'hex'], \str_split($bytes)));
    }

    public static function prettyCharacter(string $character): string
    {
        $ord = \ord($character);
        if ($ord > 31 && $ord !== 127) {
            return $character;
        }
        return self::tryPrettyCharacter($ord) ?? self::hex($character);
    }

    public static function tryPrettyCharacter(int $ord): ?string
    {
        return self::getFromArrayOrNull($ord, [
            8  => '\b',
            9  => '\t',
            10 => '\n',
            11 => '\v',
            12 => '\f',
            13 => '\r',
            27 => '\e',
        ]);
    }

    public static function getFromArrayOrNull(int $key, array $map): ?string
    {
        return \array_key_exists($key, $map) ? $map[$key] : null;
    }

    private static function hex(string $character): string
    {
        return '\x' . \dechex(\ord($character));
    }
}
