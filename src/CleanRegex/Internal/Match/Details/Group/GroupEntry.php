<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupEntry
{
    /** @var string */
    private $text;
    /** @var int */
    private $byteOffset;
    /** @var Subjectable */
    private $subject;

    public function __construct(string $text, int $byteOffset, Subjectable $subject)
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
