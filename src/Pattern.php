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

    /**
     * @return string[]
     */
    public function search(string $subject): array
    {
        \preg_match_all("/$this->pattern/", $subject, $matches);
        return $matches[0];
    }

    public function replace(string $subject, string $replacement): string
    {
        return \preg_replace(
            "/$this->pattern/",
            \str_replace(['\\', '$'], ['\\\\', '\$'], $replacement),
            $subject);
    }

    /**
     * @return string[]
     */
    public function split(string $subject): array
    {
        return \preg_split("/$this->pattern/", $subject, -1,
            \PREG_SPLIT_DELIM_CAPTURE);
    }
}
