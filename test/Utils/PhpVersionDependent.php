<?php
namespace Test\Utils;

class PhpVersionDependent
{
    public static function getUnmatchedParenthesisMessage(int $offset): string
    {
        return "/(preg_match_all\(\): )?Compilation failed: (missing|unmatched) (closing parenthesis|parentheses|\)) at offset $offset/";
    }

    public static function getUnmatchedParenthesisMessage_ReplaceCallback(int $offset)
    {
        return "/(preg_replace_callback\(\): )?Compilation failed: (missing|unmatched) (closing parenthesis|parentheses|\)) at offset $offset/";
    }
}
