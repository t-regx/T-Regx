<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;

class Coordinate
{
    /** @var MatchEntry */
    private $entry;

    public function __construct(MatchEntry $entry)
    {
        $this->entry = $entry;
    }

    public function offset(): ByteOffset
    {
        return new ByteOffset($this->entry->byteOffset());
    }

    public function tail(): ByteOffset
    {
        return new ByteOffset(\strlen($this->entry->getText()) + $this->entry->byteOffset());
    }
}
