<?php
namespace Regex;

use Regex\Internal\DelimitedExpression;

final class Pattern
{
    public const IGNORE_CASE = 'i';
    public const MULTILINE = 'm';
    public const UNICODE = 'u';
    public const COMMENTS_WHITESPACE = 'x';
    public const SINGLELINE = 's';
    public const INVERTED_GREEDY = 'U';
    public const DUPLICATE_NAMES = 'J';

    private DelimitedExpression $expression;

    public function __construct(string $pattern, string $modifiers = '')
    {
        $this->expression = new DelimitedExpression($pattern, $modifiers);
    }

    public function test(string $subject): bool
    {
        $result = \preg_match($this->expression->delimited, $subject);
        $this->throwMatchException();
        return $result === 1;
    }

    /**
     * @return string[]
     */
    public function search(string $subject): array
    {
        \preg_match_all($this->expression->delimited, $subject, $matches);
        $this->throwMatchException();
        return $matches[0];
    }

    public function replace(string $subject, string $replacement): string
    {
        $result = \preg_replace(
            $this->expression->delimited,
            \str_replace(['\\', '$'], ['\\\\', '\$'], $replacement),
            $subject);
        $this->throwMatchException();
        return $result;
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
        $this->throwMatchException();
        $result = [];
        foreach ($pieces as [$piece, $offset]) {
            $result[] = $offset === -1 ? null : $piece;
        }
        return $result;
    }

    private function throwMatchException(): void
    {
        $error = \preg_last_error();
        if ($error === \PREG_BACKTRACK_LIMIT_ERROR) {
            throw new BacktrackException();
        }
        if ($error === \PREG_RECURSION_LIMIT_ERROR) {
            throw new RecursionException();
        }
        if ($error === \PREG_JIT_STACKLIMIT_ERROR) {
            throw new JitException();
        }
    }
}
