<?php
namespace Regex;

use Regex\Internal\GroupKey;
use Regex\Internal\GroupKeys;
final class Detail
{
    private string $text;
    private int $offset;
    private string $subject;
    private GroupKeys $groupKeys;
    private array $match;

    public function __construct(array $match, string $subject, GroupKeys $groupKeys)
    {
        [[$this->text, $this->offset]] = $match;
        $this->subject = $subject;
        $this->groupKeys = $groupKeys;
        $this->match = $match;
    }

    public function text(): string
    {
        return $this->text;
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

    public function groupExists($nameOrIndex): bool
    {
        return $this->groupKeys->groupExists(new GroupKey($nameOrIndex));
    }

    public function groupMatched($nameOrIndex): bool
    {
        $group = new GroupKey($nameOrIndex);
        if ($this->groupKeys->groupExists($group)) {
            if (\array_key_exists($nameOrIndex, $this->match)) {
                return $this->match[$nameOrIndex][1] !== -1;
            }
            return false;
        }
        throw new GroupException($group);
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
