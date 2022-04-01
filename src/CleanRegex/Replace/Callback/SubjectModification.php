<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;

class SubjectModification implements Modification
{
    /** @var string */
    private $subject;
    /** @var ByteOffset */
    private $byteOffset;

    public function __construct(string $subject, int $byteOffset)
    {
        $this->subject = $subject;
        $this->byteOffset = new ByteOffset($byteOffset);
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function offset(): int
    {
        return $this->byteOffset->characters($this->subject);
    }

    public function byteOffset(): int
    {
        return $this->byteOffset->bytes();
    }
}
