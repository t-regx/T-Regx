<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class ArrayOnlyStream implements Stream
{
    use PreservesKey;

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
        $mapper = $this->mapper;
        return $mapper($this->stream->all());
    }

    public function first()
    {
        return $this->stream->first();
    }
}
