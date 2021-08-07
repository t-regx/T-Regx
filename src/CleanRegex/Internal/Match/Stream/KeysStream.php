<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class KeysStream implements Stream
{
    /** @var Stream */
    private $stream;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    public function all(): array
    {
        return \array_keys($this->stream->all());
    }

    public function first()
    {
        return $this->stream->firstKey();
    }

    public function firstKey(): int
    {
        $this->stream->firstKey();
        return 0;
    }
}
