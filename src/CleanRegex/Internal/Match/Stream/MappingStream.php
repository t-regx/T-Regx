<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class MappingStream implements Stream
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
        return \array_map($this->mapper, $this->stream->all());
    }

    public function first()
    {
        $mapper = $this->mapper;
        return $mapper($this->stream->first());
    }

    public function firstKey()
    {
        return $this->stream->firstKey();
    }
}
