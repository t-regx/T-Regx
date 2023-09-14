<?php
namespace Regex;

final class Pattern
{
    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function test(string $subject): bool
    {
        return \preg_match("/$this->pattern/", $subject) === 1;
    }
}
