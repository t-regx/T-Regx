<?php
namespace Regex;

use Regex\Internal\DelimitedExpression;
use Regex\Internal\GroupKey;
use Regex\Internal\GroupKeys;
use Regex\Internal\GroupNames;
use Regex\Internal\Pcre;
use Regex\Internal\ReplaceFunction;

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
    private GroupNames $groupNames;
    private GroupKeys $groupKeys;

    public function __construct(string $pattern, string $modifiers = '')
    {
        $this->expression = new DelimitedExpression($pattern, $modifiers);
        $this->pcre = new Pcre($this->expression);
        $this->groupNames = new GroupNames($this->expression);
        $this->groupKeys = new GroupKeys($this->expression);
    }

    public function test(string $subject): bool
    {
        return $this->pcre->test($subject);
    }

    public function count(string $subject): int
    {
        return $this->pcre->count($subject);
    }

    public function first(string $subject): Detail
    {
        $detail = $this->firstOrNull($subject);
        if ($detail === null) {
            throw new NoMatchException();
        }
        return $detail;
    }

    public function firstOrNull(string $subject): ?Detail
    {
        $match = $this->pcre->matchFirst($subject);
        if (empty($match)) {
            return null;
        }
        return new Detail($match, $subject, $this->groupKeys, 0);
    }

    /**
     * @return string[]
     */
    public function search(string $subject): array
    {
        return $this->pcre->search($subject)[0];
    }

    /**
     * @return string[]|null[]
     */
    public function searchGroup(string $subject, $nameOrIndex): array
    {
        $group = new GroupKey($nameOrIndex);
        if (\in_array($nameOrIndex, $this->expression->groupKeys, true)) {
            $index = $this->groupKeys->unambiguousIndex($group);
            return $this->pcre->search($subject)[$index];
        }
        throw new GroupException($group, 'does not exist');
    }

    public function match(string $subject): Matcher
    {
        return new Matcher($this->pcre, $subject, $this->groupKeys);
    }

    public function replace(string $subject, string $replacement): string
    {
        return $this->pcre->replace($subject, $replacement)[0];
    }

    /**
     * @return string[]|int[]
     */
    public function replaceCount(string $subject, string $replacement): array
    {
        return $this->pcre->replace($subject, $replacement);
    }

    public function replaceCallback(string $subject, callable $replacer): string
    {
        return $this->pcre->replaceCallback($subject,
            [new ReplaceFunction($replacer, $subject, $this->groupKeys), 'apply']);
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

    /**
     * @param string[] $subjects
     * @return string[]
     */
    public function filter(array $subjects): array
    {
        return $this->pcre->filter($subjects);
    }

    /**
     * @param string[] $subjects
     * @return string[]
     */
    public function reject(array $subjects): array
    {
        return $this->pcre->reject($subjects);
    }

    /**
     * @return string[]|null[]
     */
    public function groupNames(): array
    {
        return $this->groupNames->names;
    }

    /**
     * @param string|int $nameOrIndex
     */
    public function groupExists($nameOrIndex): bool
    {
        return $this->groupKeys->groupExists(new GroupKey($nameOrIndex));
    }

    public function groupCount(): int
    {
        return \count(\array_filter($this->expression->groupKeys, '\is_int')) - 1;
    }

    public function delimited(): string
    {
        return $this->expression->delimited;
    }

    public function __toString(): string
    {
        return $this->expression->delimited;
    }
}
