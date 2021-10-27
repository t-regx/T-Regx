<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class UniqueStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
    }

    public function all(): array
    {
        return \array_unique($this->upstream->all());
    }

    public function first()
    {
        return $this->upstream->first();
    }
}
