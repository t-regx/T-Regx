<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;

class FlatMappingStream implements Stream
{
    /** @var Stream */
    private $stream;
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var callable */
    private $mapper;
    /** @var string */
    private $methodName;

    public function __construct(Stream $stream, FlatMapStrategy $strategy, callable $mapper, string $methodName)
    {
        $this->stream = $stream;
        $this->strategy = $strategy;
        $this->mapper = $mapper;
        $this->methodName = $methodName;
    }

    public function all(): array
    {
        return (new FlatMapper($this->stream->all(), $this->strategy, $this->mapper, $this->methodName))->get();
    }

    public function first()
    {
        return (new FlatMapper([], $this->strategy, $this->mapper, $this->methodName))->map($this->stream->first());
    }

    public function firstKey()
    {
        return $this->stream->firstKey();
    }
}
