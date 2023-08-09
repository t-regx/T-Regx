<?php
namespace Regex;

final class Pattern
{
    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        @\preg_match("/$this->pattern/", '');
        $error = \error_get_last();
        if ($error !== null) {
            throw new SyntaxException($this->exceptionMessage($error['message']));
        }
    }

    private function exceptionMessage(string $message): string
    {
        return \ucFirst(\subStr($message, \strLen('preg_match(): Compilation failed: '))) . '.';
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
     * @return string[]|null[]
     */
    public function split(string $subject, int $maxSplits = -1): array
    {
        if ($maxSplits < 0) {
            return $this->splitSubject($subject, -1);
        }
        return $this->splitSubject($subject, $maxSplits + 1);
    }

    private function splitSubject(string $subject, int $limit): array
    {
        $pieces = \preg_split("/$this->pattern/", $subject, $limit,
            \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_OFFSET_CAPTURE);
        $result = [];
        foreach ($pieces as [$piece, $offset]) {
            $result[] = $offset === -1 ? null : $piece;
        }
        return $result;
    }
}
