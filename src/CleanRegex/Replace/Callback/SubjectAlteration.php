<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\Replace\Details\Modification;
use TRegx\CleanRegex\Internal\Subject;

class SubjectAlteration
{
    /** @var string */
    private $subject;
    /** @var int */
    private $byteOffset;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject->asString();
        $this->byteOffset = 0;
    }

    public function modify(string $text, int $byteOffset, string $replacement): void
    {
        $this->subject = \substr_replace($this->subject, $replacement, $byteOffset + $this->byteOffset, \strLen($text));
        $this->byteOffset += \strLen($replacement) - \strLen($text);
    }

    public function modification(int $byteOffset): Modification
    {
        return new SubjectModification($this->subject, $this->byteOffset + $byteOffset);
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }

    public function subject(): string
    {
        return $this->subject;
    }
}
