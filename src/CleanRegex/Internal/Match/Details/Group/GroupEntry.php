<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Internal\Subject;

class GroupEntry implements Entry
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
        $this->tail = new ByteOffset($byteOffset + \strLen($this->text));
    }

    public function text(): string
    {
        return $this->text;
    }

    public function offset(): int
    {
        return $this->offset->characters($this->subject);
    }

    public function byteOffset(): int
    {
        return $this->offset->bytes();
    }

    public function tail(): int
    {
        return $this->tail->characters($this->subject);
    }

    public function byteTail(): int
    {
        return $this->tail->bytes();
    }
}
