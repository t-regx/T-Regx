<?php
namespace Regex\Internal;

use Regex\BacktrackException;
use Regex\JitException;
use Regex\PcreException;
use Regex\RecursionException;
use Regex\RegexException;
use Regex\UnicodeException;

class Pcre
{
    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function test(string $subject): bool
    {
        \preg_replace($this->pattern, '\0', $subject, 1, $count);
        $this->throwMatchException();
        return $count > 0;
    }

    public function count(string $subject): int
    {
        \preg_replace($this->pattern, '\0', $subject, -1, $count);
        $this->throwMatchException();
        return $count;
    }

    public function matchFirst(string $subject): array
    {
        $error = false;
        \set_error_handler(static function () use (&$error): void {
            $error = true;
        });
        \preg_match($this->pattern, $subject, $match,
            \PREG_OFFSET_CAPTURE);
        \restore_error_handler();
        if ($error) {
            throw new PcreException('undetermined error');
        }
        $this->throwMatchException();
        return $match;
    }

    public function search(string $subject): array
    {
        $error = false;
        \set_error_handler(static function () use (&$error): void {
            $error = true;
        });
        \preg_match_all($this->pattern, $subject, $matches,
            \PREG_UNMATCHED_AS_NULL);
        \restore_error_handler();
        if ($error) {
            throw new PcreException('undetermined error');
        }
        $this->throwMatchException();
        return $matches;
    }

    public function fullMatchWithException(string $subject): array
    {
        $error = false;
        \set_error_handler(static function () use (&$error): void {
            $error = true;
        });
        \preg_match_all($this->pattern, $subject, $matches,
            \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
        \restore_error_handler();
        if ($error) {
            return [$matches, new PcreException('undetermined error')];
        }
        return [$matches, $this->lastMatchException()];
    }

    public function replace(string $subject, string $replacement, int $limit): array
    {
        $result = \preg_replace($this->pattern,
            \str_replace(['\\', '$'], ['\\\\', '\$'], $replacement),
            $subject, $limit, $count);
        $this->throwMatchException();
        return [$result, $count];
    }

    public function replaceCallback(string $subject, Replacer $replacer, int $limit): string
    {
        $result = \preg_replace_callback(
            $this->pattern, [$replacer, 'replace'],
            $subject, $limit, $count, \PREG_OFFSET_CAPTURE);
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
        $pieces = \preg_split($this->pattern, $subject, $limit,
            \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_OFFSET_CAPTURE);
        $this->throwMatchException();
        return $pieces;
    }

    public function filter(array $subjects): array
    {
        $result = \preg_filter($this->pattern, '\0', $subjects);
        $this->throwMatchException();
        return $result;
    }

    private function throwMatchException(): void
    {
        $exception = $this->lastMatchException();
        if ($exception) {
            throw $exception;
        }
    }

    private function lastMatchException(): ?RegexException
    {
        $error = \preg_last_error();
        if ($error === \PREG_INTERNAL_ERROR) {
            return new PcreException('pcre internal error');
        }
        if ($error === \PREG_BACKTRACK_LIMIT_ERROR) {
            return new BacktrackException();
        }
        if ($error === \PREG_RECURSION_LIMIT_ERROR) {
            return new RecursionException();
        }
        if ($error === \PREG_BAD_UTF8_ERROR) {
            return new UnicodeException('Malformed unicode subject.');
        }
        if ($error === \PREG_JIT_STACKLIMIT_ERROR) {
            return new JitException();
        }
        return null;
    }
}
