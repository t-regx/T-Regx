<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\Limit;

class LimitStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var Limit */
    private $limit;

    public function __construct(Upstream $upstream, Limit $limit)
    {
        $this->upstream = $upstream;
        $this->limit = $limit;
    }

    public function all(): array
    {
        return \array_slice($this->upstream->all(), 0, $this->limit->intValue(), true);
    }

    public function first(): array
    {
        $first = $this->upstream->first();
        if ($this->limit->empty()) {
            throw new EmptyStreamException();
        }
        return $first;
    }
}
