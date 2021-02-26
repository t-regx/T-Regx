<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class BaseStream
{
    /** @var Base */
    private $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    public function all(): RawMatchesOffset
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->matched()) {
            return $matches;
        }
        throw new UnmatchedStreamException();
    }

    public function first(): IRawMatchOffset
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $match;
        }
        throw new UnmatchedStreamException();
    }

    public function firstKey(): int
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $match->getIndex();
        }
        throw new UnmatchedStreamException();
    }
}
