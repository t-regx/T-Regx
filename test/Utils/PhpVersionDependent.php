<?php
namespace Test\Utils;

class PhpVersionDependent
{
    public static function getUnmatchedParenthesisMessage(int $offset): string
    {
        $closing = "Compilation failed: unmatched closing parenthesis at offset $offset";
        if (PHP_VERSION_ID === 70300) {
            return $closing;
        }
        if (PHP_VERSION_ID < 70400) {
            return "Compilation failed: unmatched parentheses at offset $offset";
        }
        return $closing;
    }

    public static function getUnmatchedParenthesisMessage_ReplaceCallback(int $offset)
    {
        $closing = "preg_replace_callback(): Compilation failed: missing closing parenthesis at offset $offset";
        if (PHP_VERSION_ID === 70300) {
            return $closing;
        }
        if (PHP_VERSION_ID < 70400) {
            return "preg_replace_callback(): Compilation failed: missing ) at offset $offset";
        }
        return $closing;
    }
}
