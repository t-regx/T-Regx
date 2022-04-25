<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class KeyStream implements Upstream
{
    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
    }

    public function all(): array
    {
        return \array_keys($this->upstream->all());
    }

    public function first()
    {
        return $this->upstream->firstKey();
    }

    public function firstKey(): int
    {
        $this->upstream->firstKey();
        return 0;
    }
}
