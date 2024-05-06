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

    public function __construct(string $text, int $offset, string $subject, GroupKeys $groupKeys)
    {
        $this->text = $text;
        $this->offset = $offset;
        $this->subject = $subject;
        $this->groupKeys = $groupKeys;
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

    public function __toString(): string
    {
        return $this->text;
    }
}
