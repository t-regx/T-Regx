<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

trait PreservesKey
{
    /** @var Stream */
    private $stream;

    public function firstKey()
    {
        return $this->stream->firstKey();
    }
}
