<?php
namespace TRegx\SafeRegex\Internal;

class Bug
{
    /**
     * @template T of string|string[]|mixed
     * @param T $pattern
     * @return T
     */
    public static function fix($pattern)
    {
        if (\is_string($pattern)) {
            return self::map($pattern);
        }
        if (\is_array($pattern)) {
            return self::mapArray($pattern);
        }
        return $pattern;
    }

    private static function map(string $pattern): string
    {
        return \rTrim($pattern, "\r\t\f\x0b");
    }

    /**
     * @param string[] $pattern
     * @return string[]
     */
    private static function mapArray(array $pattern): array
    {
        return \array_map([Bug::class, 'map'], $pattern);
    }

    /**
     * @template T
     * @param array<string, T> $patterns
     * @return array<string, T>
     */
    public static function fixArrayKeys(array $patterns): array
    {
        $result = [];
        foreach ($patterns as $pattern => $mapper) {
            $result[self::map($pattern)] = $mapper;
        }
        return $result;
    }
}
