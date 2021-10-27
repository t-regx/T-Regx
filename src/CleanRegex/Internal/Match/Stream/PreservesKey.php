<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

trait PreservesKey
{
    /** @var Upstream */
    private $upstream;

    public function firstKey()
    {
        return $this->upstream->firstKey();
    }
}
