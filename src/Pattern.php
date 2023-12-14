<?php
namespace Regex;

use Regex\Internal\DelimitedExpression;
use Regex\Internal\Pcre;

final class Pattern
{
    public const IGNORE_CASE = 'i';
    public const MULTILINE = 'm';
    public const UNICODE = 'u';
    public const COMMENTS_WHITESPACE = 'x';
    public const SINGLELINE = 's';
    public const ANCHORED = 'A';
    public const INVERTED_GREEDY = 'U';
    public const DUPLICATE_NAMES = 'J';

    private DelimitedExpression $expression;
    private Pcre $pcre;

    public function __construct(string $pattern, string $modifiers = '')
    {
        $this->expression = new DelimitedExpression($pattern, $modifiers);
        $this->pcre = new Pcre($this->expression);
    }

    public function test(string $subject): bool
    {
        return $this->pcre->test($subject);
    }

    public function count(string $subject): int
    {
        return $this->pcre->count($subject);
    }

    /**
     * @return string[]
     */
    public function search(string $subject): array
    {
        return $this->pcre->search($subject);
    }

    public function replace(string $subject, string $replacement): string
    {
        return $this->pcre->replace($subject, $replacement);
    }

    /**
     * @return string[]|null[]
     */
    public function split(string $subject, int $maxSplits = -1): array
    {
        if ($maxSplits < 0) {
            return $this->pcre->split($subject, -1);
        }
        return $this->pcre->split($subject, $maxSplits + 1);
    }

    public function groupCount(): int
    {
        return \count(\array_filter($this->expression->groupKeys, '\is_int')) - 1;
    }

    public function __toString(): string
    {
        return $this->expression->delimited;
    }
}
