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

    public function first(): array
    {
        [$key] = $this->upstream->first();
        return [0, $key];
    }
}
