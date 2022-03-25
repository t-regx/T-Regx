<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;

class FlatMapStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var FlatFunction */
    private $function;

    public function __construct(Upstream $upstream, FlatMapStrategy $strategy, FlatFunction $function)
    {
        $this->upstream = $upstream;
        $this->strategy = $strategy;
        $this->function = $function;
    }

    public function all(): array
    {
        return $this->strategy->flatten($this->function->map($this->upstream->all()));
    }

    public function first()
    {
        $flatMap = $this->flatMapTryFirstOrAll();
        if (!empty($flatMap)) {
            return \reset($flatMap);
        }
        throw new EmptyStreamException();
    }

    public function firstKey()
    {
        $flatMap = $this->flatMapTryFirstOrAll();
        \reset($flatMap);
        $firstKey = \key($flatMap);
        if ($firstKey !== null) {
            return $firstKey;
        }
        throw new EmptyStreamException();
    }

    private function flatMapTryFirstOrAll(): array
    {
        $mapped = $this->function->apply($this->upstream->first());
        if (empty($mapped)) {
            return $this->all();
        }
        return $mapped;
    }
}
