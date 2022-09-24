<?php
namespace TRegx\CleanRegex\Internal;

class VisibleCharacters
{
    /** @var string */
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        $result = \preg_replace_callback("#\\p{C}|\xc2\xa0#u", static function (array $matches) {
            return self::formatWord($matches[0]);
        }, $this->string);
        if (\preg_last_error() === \PREG_BAD_UTF8_ERROR) {
            \preg_match('//', '');
            return \join(\array_map([self::class, 'formatByteOrAscii'], \str_split($this->string)));
        }
        return $result;
    }

    private static function formatWord(string $bytes): string
    {
        if (\strLen($bytes) === 1) {
            return self::tryPrettyByte(\ord($bytes)) ?? self::hex($bytes);
        }
        return \join(\array_map([self::class, 'hex'], \str_split($bytes)));
    }

    private static function formatByteOrAscii(string $character): string
    {
        $ord = \ord($character);
        if ($ord > 31 && $ord < 127) {
            return $character;
        }
        return self::tryPrettyByte($ord) ?? self::hex($character);
    }

    private static function tryPrettyByte(int $ord): ?string
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

    private static function getFromArrayOrNull(int $key, array $map): ?string
    {
        return \array_key_exists($key, $map) ? $map[$key] : null;
    }

    private static function hex(string $character): string
    {
        return '\x' . \dechex(\ord($character));
    }
}
