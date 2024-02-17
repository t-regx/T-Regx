<?php
namespace Regex;

use Regex\Internal\DelimitedExpression;
use Regex\Internal\GroupKey;
use Regex\Internal\GroupKeys;
use Regex\Internal\GroupNames;
use Regex\Internal\Modifiers;
use Regex\Internal\Pcre;
use Regex\Internal\ReplaceFunction;
use Regex\Internal\ReplaceGroup;

final class Pattern
{
    public const IGNORE_CASE = 'i';
    public const MULTILINE = 'm';
    public const UNICODE = 'u';
    public const COMMENTS_WHITESPACE = 'x';
    public const EXPLICIT_CAPTURE = 'n';
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
        $this->expression = new DelimitedExpression($pattern, new Modifiers($modifiers));
        $this->pcre = new Pcre($this->expression->delimited);
        $this->groupNames = new GroupNames($this->expression->groupKeys);
        $this->groupKeys = new GroupKeys($this->expression->groupKeys);
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

    /**
     * @return Detail[]|\Iterator
     */
    public function matchPartial(string $subject): \Iterator
    {
        [$matches, $exception] = $this->pcre->fullMatchWithException($subject);
        foreach ($matches as $index => $match) {
            yield new Detail($match, $subject, $this->groupKeys, $index);
        }
        if ($exception) {
            throw $exception;
        }
    }

    public function replace(string $subject, string $replacement, int $limit = -1): string
    {
        return $this->pcre->replace($subject, $replacement, $limit)[0];
    }

    /**
     * @return string[]|int[]
     */
    public function replaceCount(string $subject, string $replacement, int $limit = -1): array
    {
        return $this->pcre->replace($subject, $replacement, $limit);
    }

    public function replaceGroup(string $subject, $nameOrIndex, int $limit = -1): string
    {
        return $this->pcre->replaceCallback($subject,
            new ReplaceGroup($this->groupKeys, new GroupKey($nameOrIndex)), $limit);
    }

    public function replaceCallback(string $subject, callable $replacer, int $limit = -1): string
    {
        return $this->pcre->replaceCallback($subject,
            new ReplaceFunction($replacer, $subject, $this->groupKeys), $limit);
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
        return \array_diff_key($subjects, $this->filter($subjects));
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
