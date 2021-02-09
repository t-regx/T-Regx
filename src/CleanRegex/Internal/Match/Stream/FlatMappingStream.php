<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;

class FlatMappingStream implements Stream
{
    /** @var Stream */
    private $stream;
    /** @var FlatMapper */
    private $flatMapper;

    public function __construct(ValueStream $stream, FlatMapStrategy $strategy, callable $mapper, string $methodName)
    {
        $this->stream = $stream;
        $this->flatMapper = new FlatMapper($strategy, $mapper, $methodName);
    }

    public function all(): array
    {
        return $this->flatMapper->get($this->stream->all());
    }

    public function first()
    {
        $flatMap = $this->flatMapTryFirstOrAll();
        if (!empty($flatMap)) {
            return \reset($flatMap);
        }
        throw new NoFirstStreamException();
    }

    public function firstKey()
    {
        $flatMap = $this->flatMapTryFirstOrAll();
        \reset($flatMap);
        $firstKey = \key($flatMap);
        if ($firstKey !== null) {
            return $firstKey;
        }
        throw new NoFirstStreamException();
    }

    private function flatMapTryFirstOrAll(): array
    {
        $mappedInFirstIteration = $this->flatMapper->map($this->stream->first());
        if (empty($mappedInFirstIteration)) {
            return $this->flatMapper->get($this->stream->all());
        }
        return $mappedInFirstIteration;
    }
}
