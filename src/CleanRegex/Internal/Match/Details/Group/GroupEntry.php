<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Subject;

class GroupEntry
{
    /** @var string */
    private $text;
    /** @var int */
    private $byteOffset;
    /** @var Subject */
    private $subject;

    public function __construct(string $text, int $byteOffset, Subject $subject)
    {
        $this->text = $text;
        $this->byteOffset = $byteOffset;
        $this->subject = $subject;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->subject->getSubject(), $this->byteOffset);
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }

    public function tail(): int
    {
        return ByteOffset::toCharacterOffset($this->subject->getSubject(), $this->byteTail());
    }

    public function byteTail(): int
    {
        return $this->byteOffset + \strlen($this->text);
    }
}
