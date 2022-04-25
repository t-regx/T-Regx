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

    public function first(): array
    {
        [$firstKey, $firstValue] = $this->upstream->first();
        if ($this->predicate->test($firstValue)) {
            return [$firstKey, $firstValue];
        }
        return $this->remainingEntries();
    }

    private function remainingEntries(): array
    {
        foreach ($this->shifted() as $key => $value) {
            if ($this->predicate->test($value)) {
                return [$key, $value];
            }
        }
        throw new EmptyStreamException();
    }

    private function shifted(): array
    {
        return \array_slice($this->upstream->all(), 1, null, true);
    }
}
