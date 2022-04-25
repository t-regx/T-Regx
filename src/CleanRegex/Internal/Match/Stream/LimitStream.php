<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class LimitStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var int */
    private $limit;

    public function __construct(Upstream $upstream, int $limit)
    {
        $this->upstream = $upstream;
        $this->limit = $limit;
    }

    public function all(): array
    {
        return \array_slice($this->upstream->all(), 0, $this->limit, true);
    }

    public function first(): array
    {
        $first = $this->upstream->first();
        if ($this->limit === 0) {
            throw new EmptyStreamException();
        }
        return $first;
    }
}
