<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

trait PreservesKey
{
    /** @var Upstream */
    private $stream;

    public function firstKey()
    {
        return $this->stream->firstKey();
    }
}
