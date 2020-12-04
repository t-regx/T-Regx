<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\FlatMapper;

class FlatMappingStream implements Stream
{
    /** @var Stream */
    private $stream;
    /** @var callable */
    private $mapper;

    public function __construct(Stream $stream, callable $mapper)
    {
        $this->stream = $stream;
        $this->mapper = $mapper;
    }

    public function all(): array
    {
        return (new FlatMapper($this->stream->all(), $this->mapper))->get();
    }

    public function first()
    {
        return (new FlatMapper([], $this->mapper))->map($this->stream->first());
    }

    public function firstKey()
    {
        return $this->stream->firstKey();
    }
}
