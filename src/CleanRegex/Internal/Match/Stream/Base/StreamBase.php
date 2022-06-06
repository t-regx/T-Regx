<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;

class StreamBase
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

    public function first(): RawMatchOffset
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $match;
        }
        throw new UnmatchedStreamException();
    }
}
