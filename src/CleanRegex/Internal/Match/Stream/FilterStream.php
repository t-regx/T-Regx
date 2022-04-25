<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Predicate;

class FilterStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var Predicate */
    private $predicate;

    public function __construct(Upstream $upstream, Predicate $predicate)
    {
        $this->upstream = $upstream;
        $this->predicate = $predicate;
    }

    public function all(): array
    {
        return \array_filter($this->upstream->all(), [$this->predicate, 'test']);
    }

    public function first()
    {
        [$key, $first] = $this->firstEntry();
        return $first;
    }

    public function firstKey()
    {
        [$key] = $this->firstEntry();
        return $key;
    }

    private function firstEntry(): array
    {
        $first = $this->upstream->first();
        if ($this->predicate->test($first)) {
            return [$this->upstream->firstKey(), $first];
        }
        foreach ($this->shifted() as $key => $item) {
            if ($this->predicate->test($item)) {
                return [$key, $item];
            }
        }
        throw new EmptyStreamException();
    }

    private function shifted(): array
    {
        return \array_slice($this->upstream->all(), 1, null, true);
    }
}
