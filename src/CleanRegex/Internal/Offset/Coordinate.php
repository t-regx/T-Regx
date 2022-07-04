<?php
namespace TRegx\CleanRegex\Internal\Offset;

use TRegx\CleanRegex\Internal\Model\Entry;

class Coordinate
{
    /** @var Entry */
    private $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function offset(): ByteOffset
    {
        return new ByteOffset($this->entry->byteOffset());
    }

    public function tail(): ByteOffset
    {
        return new ByteOffset(\strLen($this->entry->text()) + $this->entry->byteOffset());
    }
}
