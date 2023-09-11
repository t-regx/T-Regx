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
        $leading = \subStr($this->subject, 0, $this->offset);
        if (\mb_check_encoding($leading, 'UTF-8')) {
            return \mb_strLen($leading, 'UTF-8');
        }
        throw new UnicodeException("Byte offset $this->offset does not point to a valid unicode code point.");
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
        $group = new GroupKey($nameOrIndex);
        if (!$this->match->groupExists($group)) {
            throw new GroupException($group, 'does not exist');
        }
        if (!$this->match->groupMatched($group)) {
            return null;
        }
        return $this->match->groupText($group);
    }

    public function groupExists($nameOrIndex): bool
    {
        return $this->match->groupExists(new GroupKey($nameOrIndex));
    }

    public function groupMatched($nameOrIndex): bool
    {
        $group = new GroupKey($nameOrIndex);
        if ($this->match->groupExists($group)) {
            return $this->match->groupMatched($group);
        }
        throw new GroupException($group, 'does not exist');
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
