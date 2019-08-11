<?php
namespace TRegx\CleanRegex\Internal;

class Integer
{
    public static function isValid(string $value): bool
    {
        if ($value === "") {
            return false;
        }
        if (\trim($value) !== $value) {
            return false;
        }
        if (\filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return $value[0] !== '+';
        }
        $text = \ltrim($value, '0');
        if ($text === "") {
            return true;
        }
        return \filter_var($text, FILTER_VALIDATE_INT) !== false;
    }
}
