<?php
namespace TRegx\SafeRegex\Internal;

class Bug
{
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

    private static function mapArray(array $pattern): array
    {
        return \array_map([Bug::class, 'map'], $pattern);
    }

    public static function fixArrayKeys(array $patterns): array
    {
        $result = [];
        foreach ($patterns as $pattern => $mapper) {
            $result[self::map($pattern)] = $mapper;
        }
        return $result;
    }
}
