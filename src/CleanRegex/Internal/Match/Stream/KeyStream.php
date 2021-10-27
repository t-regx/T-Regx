<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class KeyStream implements Upstream
{
    use ListStream;

    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
    }

    protected function entries(): array
    {
        return \array_keys($this->upstream->all());
    }

    protected function firstValue()
    {
        return $this->upstream->firstKey();
    }
}
