<?php
namespace Test\Utils;

class PhpVersionDependent
{
    public static function getUnmatchedParenthesisMessage(int $offset): string
    {
        return "/(preg_match_all\(\): )?(Compilation failed: )?([Mm]issing|[Uu]nmatched) (closing parenthesis|parentheses|\)) at offset $offset/";
    }
}
