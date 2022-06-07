<?php
namespace Test\Utils\Agnostic;

trait PhpVersionDependent
{
    public static function unmatchedParenthesisMessage(int $offset): string
    {
        return "/(preg_match_all\(\): )?(Compilation failed: )?([Mm]issing|[Uu]nmatched) (closing parenthesis|parentheses|\)) at offset $offset/";
    }
}
