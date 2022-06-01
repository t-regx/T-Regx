<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;

class MatchEntry implements Entry
{
    /** @var RawMatchOffset */
    private $matchOffset;

    public function __construct(RawMatchOffset $matchOffset)
    {
        $this->matchOffset = $matchOffset;
    }

    public function text(): string
    {
        return $this->matchOffset->getText();
    }

    public function byteOffset(): int
    {
        return $this->matchOffset->byteOffset();
    }
}
