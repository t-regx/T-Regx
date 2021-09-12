<?php
namespace Test\Fakes\CleanRegex\Internal\Model\Match;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\Entry;

class OffsetEntry implements Entry
{
    /** @var int */
    private $offset;

    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    public function text(): string
    {
        throw new AssertionError("Failed to assert that Entry.getText() wasn't used");
    }

    public function byteOffset(): int
    {
        return $this->offset;
    }
}
