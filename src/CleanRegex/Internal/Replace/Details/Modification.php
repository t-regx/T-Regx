<?php
namespace TRegx\CleanRegex\Internal\Replace\Details;

use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;

class Modification
{
    /** @var Entry */
    private $entry;
    /** @var string */
    private $subject;
    /** @var int */
    private $byteOffset;

    public function __construct(Entry $entry, string $subject, int $byteOffset)
    {
        $this->entry = $entry;
        $this->subject = $subject;
        $this->byteOffset = $byteOffset;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function offset(): int
    {
        $offset = new ByteOffset($this->byteOffset());
        return $offset->characters($this->subject);
    }

    public function byteOffset(): int
    {
        return $this->entry->byteOffset() + $this->byteOffset;
    }
}
