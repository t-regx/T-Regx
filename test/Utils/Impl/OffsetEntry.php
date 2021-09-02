<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;

class OffsetEntry implements MatchEntry
{
    /** @var int */
    private $offset;

    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    public function getText(): string
    {
        throw new AssertionError("Failed to assert that Entry.getText() wasn't used");
    }

    public function byteOffset(): int
    {
        return $this->offset;
    }
}
