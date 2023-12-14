<?php
namespace Regex\Internal;

use Regex\BacktrackException;
use Regex\JitException;
use Regex\PcreException;
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
        \preg_replace($this->expression->delimited, '\0', $subject, 1, $count);
        $this->throwMatchException();
        return $count > 0;
    }

    public function count(string $subject): int
    {
        \preg_replace($this->expression->delimited, '\0', $subject, -1, $count);
        $this->throwMatchException();
        return $count;
    }

    public function matchFirst(string $subject): array
    {
        \error_clear_last();
        @\preg_match($this->expression->delimited, $subject, $match,
            \PREG_OFFSET_CAPTURE);
        if (\error_get_last() !== null) {
            throw new PcreException('undetermined error');
        }
        $this->throwMatchException();
        return $match;
    }

    public function search(string $subject): array
    {
        \error_clear_last();
        @\preg_match_all($this->expression->delimited, $subject, $matches,
            \PREG_UNMATCHED_AS_NULL);
        if (\error_get_last() !== null) {
            throw new PcreException('undetermined error');
        }
        $this->throwMatchException();
        return $matches;
    }

    public function fullMatch(string $subject): array
    {
        \error_clear_last();
        @\preg_match_all($this->expression->delimited, $subject, $matches,
            \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
        if (\error_get_last() !== null) {
            throw new PcreException('undetermined error');
        }
        $this->throwMatchException();
        return $matches;
    }

    public function replace(string $subject, string $replacement): array
    {
        $result = \preg_replace($this->expression->delimited,
            \str_replace(['\\', '$'], ['\\\\', '\$'], $replacement),
            $subject, -1, $count);
        $this->throwMatchException();
        return [$result, $count];
    }

    public function replaceCallback(string $subject, callable $replacer): string
    {
        $result = \preg_replace_callback($this->expression->delimited, $replacer, $subject,
            -1, $count, \PREG_OFFSET_CAPTURE);
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

    public function filter(array $subjects): array
    {
        $result = \preg_filter($this->expression->delimited, '\0', $subjects);
        $this->throwMatchException();
        return $result;
    }

    private function throwMatchException(): void
    {
        $error = \preg_last_error();
        if ($error === \PREG_INTERNAL_ERROR) {
            throw new PcreException('pcre internal error');
        }
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
