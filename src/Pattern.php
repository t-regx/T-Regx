<?php
namespace Regex;

use Regex\Internal\DelimitedExpression;

final class Pattern
{
    private DelimitedExpression $expression;

    public function __construct(string $pattern)
    {
        $this->expression = new DelimitedExpression($pattern);
    }

    public function test(string $subject): bool
    {
        return \preg_match($this->expression->delimited, $subject) === 1;
    }

    /**
     * @return string[]
     */
    public function search(string $subject): array
    {
        \preg_match_all($this->expression->delimited, $subject, $matches);
        return $matches[0];
    }

    public function replace(string $subject, string $replacement): string
    {
        return \preg_replace(
            $this->expression->delimited,
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
        $pieces = \preg_split($this->expression->delimited, $subject, $limit,
            \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_OFFSET_CAPTURE);
        $result = [];
        foreach ($pieces as [$piece, $offset]) {
            $result[] = $offset === -1 ? null : $piece;
        }
        return $result;
    }
}
