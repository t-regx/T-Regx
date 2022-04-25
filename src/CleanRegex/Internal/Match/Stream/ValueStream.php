<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class ValueStream implements Upstream
{
    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
    }

    public function all(): array
    {
        return \array_values($this->upstream->all());
    }

    public function first(): array
    {
        [$key, $value] = $this->upstream->first();
        return [0, $value];
    }
}
