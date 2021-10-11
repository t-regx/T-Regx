<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Predicate;

class FilterStream implements Upstream
{
    use ListStream;

    /** @var Upstream */
    private $stream;
    /** @var MethodPredicate */
    private $predicate;

    public function __construct(Upstream $stream, Predicate $predicate)
    {
        $this->stream = $stream;
        $this->predicate = $predicate;
    }

    protected function entries(): array
    {
        return \array_filter($this->stream->all(), [$this->predicate, 'test']);
    }

    protected function firstValue()
    {
        $first = $this->stream->first();
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
        return \array_slice($this->stream->all(), 1);
    }
}
