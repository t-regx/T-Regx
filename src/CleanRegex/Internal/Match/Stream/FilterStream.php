<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Predicate;

class FilterStream implements Upstream
{
    use ListStream;

    /** @var MethodPredicate */
    private $predicate;
    /** @var Upstream */
    private $stream;

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
        return $this->firstElement(\array_filter($this->shifted(), [$this->predicate, 'test']));
    }

    private function shifted(): array
    {
        return \array_slice($this->stream->all(), 1);
    }

    private function firstElement(array $elements)
    {
        if (empty($elements)) {
            throw new NoFirstStreamException();
        }
        return \reset($elements);
    }
}
