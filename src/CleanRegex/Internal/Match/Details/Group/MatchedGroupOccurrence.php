<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Subjectable;

class MatchedGroupOccurrence
{
    /** @var string */
    private $text;
    /** @var int */
    private $offset;
    /**
     * @var Subjectable
     * @deprecated
     */
    public $subject;

    public function __construct(string $text, int $offset, Subjectable $subject)
    {
        $this->text = $text;
        $this->offset = $offset;
        $this->subject = $subject;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->subject->getSubject(), $this->offset);
    }

    public function byteOffset(): int
    {
        return $this->offset;
    }

    public function tail(): int
    {
        return ByteOffset::toCharacterOffset($this->subject->getSubject(), $this->byteTail());
    }

    public function byteTail(): int
    {
        return $this->offset + \strlen($this->text);
    }
}
