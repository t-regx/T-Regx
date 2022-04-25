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
        return \array_values(\array_filter($this->upstream->all(), [$this->predicate, 'test']));
    }

    public function first()
    {
        return $this->firstValue();
    }

    public function firstKey(): int
    {
        $this->firstValue();
        return 0;
    }

    private function firstValue()
    {
        $first = $this->upstream->first();
        if ($this->predicate->test($first)) {
            return $first;
        }
        foreach ($this->shifted() as $item) {
            if ($this->predicate->test($item)) {
                return $item;
            }
        }
        throw new EmptyStreamException();
    }

    private function shifted(): array
    {
        return \array_slice($this->upstream->all(), 1);
    }
}
