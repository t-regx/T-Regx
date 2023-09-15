<?php
namespace Regex\Internal;

use Regex\BacktrackException;
use Regex\JitException;
use Regex\RecursionException;
use Regex\UnicodeException;

class Pcre
{
    private DelimitedExpression $expression;

    public function __construct(DelimitedExpression $expression)
    {
        $this->expression = $expression;
    }

    public function test(string $subject): bool
    {
        $result = \preg_match($this->expression->delimited, $subject);
        $this->throwMatchException();
        return $result;
    }

    public function count(string $subject): int
    {
        $result = \preg_match_all($this->expression->delimited, $subject);
        $this->throwMatchException();
        return $result;
    }

    public function matchFirst(string $subject): array
    {
        \preg_match($this->expression->delimited, $subject, $match,
            \PREG_OFFSET_CAPTURE);
        $this->throwMatchException();
        return $match;
    }

    public function search(string $subject): array
    {
        \preg_match_all($this->expression->delimited, $subject, $matches);
        $this->throwMatchException();
        return $matches[0];
    }

    public function replace(string $subject, string $replacement): string
    {
        $result = \preg_replace($this->expression->delimited,
            \str_replace(['\\', '$'], ['\\\\', '\$'], $replacement),
            $subject);
        $this->throwMatchException();
        return $result;
    }

    public function split(string $subject, int $limit): array
    {
        $elements = [];
        foreach ($this->splitOffsetCapture($subject, $limit) as [$piece, $offset]) {
            $elements[] = $offset === -1 ? null : $piece;
        }
        return $elements;
    }

    private function splitOffsetCapture(string $subject, int $limit): array
    {
        $pieces = \preg_split($this->expression->delimited, $subject, $limit,
            \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_OFFSET_CAPTURE);
        $this->throwMatchException();
        return $pieces;
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
        if ($error === \PREG_BAD_UTF8_ERROR) {
            throw new UnicodeException('Malformed unicode subject.');
        }
        if ($error === \PREG_JIT_STACKLIMIT_ERROR) {
            throw new JitException();
        }
    }
}
