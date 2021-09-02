<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Subject;

class GroupEntry
{
    /** @var string */
    private $text;
    /** @var Subject */
    private $subject;
    /** @var ByteOffset */
    private $offset;
    /** @var ByteOffset */
    private $tail;

    public function __construct(string $text, int $byteOffset, Subject $subject)
    {
        $this->text = $text;
        $this->subject = $subject;
        $this->offset = new ByteOffset($byteOffset);
        $this->tail = new ByteOffset($byteOffset + \strlen($this->text));
    }

    public function text(): string
    {
        return $this->text;
    }

    public function offset(): int
    {
        return $this->offset->characters($this->subject->getSubject());
    }

    public function byteOffset(): int
    {
        return $this->offset->bytes();
    }

    public function tail(): int
    {
        return $this->tail->characters($this->subject->getSubject());
    }

    public function byteTail(): int
    {
        return $this->tail->bytes();
    }
}
