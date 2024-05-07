<?php
namespace Regex;

use Regex\Internal\AbsoluteMatch;
use Regex\Internal\GroupKey;
use Regex\Internal\GroupKeys;

final class Detail
{
    private string $text;
    private int $offset;
    private string $subject;
    private AbsoluteMatch $match;
    private int $index;

    public function __construct(array $match, string $subject, GroupKeys $groupKeys, int $index)
    {
        [[$this->text, $this->offset]] = $match;
        $this->subject = $subject;
        $this->match = new AbsoluteMatch($groupKeys, $match);
        $this->index = $index;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function offset(): int
    {
        return $this->unicodeOffset($this->offset);
    }

    public function byteOffset(): int
    {
        return $this->offset;
    }

    public function group($nameOrIndex): string
    {
        $group = $this->groupOrNull($nameOrIndex);
        if ($group === null) {
            throw new GroupException(new GroupKey($nameOrIndex), 'is not matched');
        }
        return $group;
    }

    public function groupOrNull($nameOrIndex): ?string
    {
        $group = $this->existingGroup($nameOrIndex);
        if (!$this->match->groupMatched($group)) {
            return null;
        }
        return $this->match->groupText($group);
    }

    public function groupOffset($nameOrIndex): int
    {
        return $this->unicodeOffset($this->groupByteOffset($nameOrIndex));
    }

    public function groupByteOffset($nameOrIndex): int
    {
        return $this->match->groupOffset($this->matchedGroup($nameOrIndex));
    }

    public function groupExists($nameOrIndex): bool
    {
        return $this->match->groupExists(new GroupKey($nameOrIndex));
    }

    public function groupMatched($nameOrIndex): bool
    {
        return $this->match->groupMatched($this->existingGroup($nameOrIndex));
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function __toString(): string
    {
        return $this->text;
    }

    private function matchedGroup($nameOrIndex): GroupKey
    {
        $group = $this->existingGroup($nameOrIndex);
        if ($this->match->groupMatched($group)) {
            return $group;
        }
        throw new GroupException(new GroupKey($nameOrIndex), 'is not matched');
    }

    private function existingGroup($nameOrIndex): GroupKey
    {
        $group = new GroupKey($nameOrIndex);
        if ($this->match->groupExists($group)) {
            return $group;
        }
        throw new GroupException($group, 'does not exist');
    }

    private function unicodeOffset(int $offset): int
    {
        $leading = \subStr($this->subject, 0, $offset);
        if (\mb_check_encoding($leading, 'UTF-8')) {
            return \mb_strLen($leading, 'UTF-8');
        }
        throw new UnicodeException("Byte offset $offset does not point to a valid unicode code point.");
    }
}
