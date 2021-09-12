<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Nested;

class FlatMappingStream implements Upstream
{
    /** @var Upstream */
    private $stream;
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var FlatFunction */
    private $function;

    public function __construct(ValueStream $stream, FlatMapStrategy $strategy, FlatFunction $function)
    {
        $this->stream = $stream;
        $this->strategy = $strategy;
        $this->function = $function;
    }

    public function all(): array
    {
        return $this->strategy->flatten(new Nested(\array_map([$this->function, 'apply'], $this->stream->all())));
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
        $mapped = $this->function->apply($this->stream->first());
        if (empty($mapped)) {
            return $this->all();
        }
        return $mapped;
    }
}
