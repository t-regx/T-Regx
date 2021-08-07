<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class KeysStream implements Stream
{
    use ListStream;

    /** @var Stream */
    private $stream;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    protected function entries(): array
    {
        return \array_keys($this->stream->all());
    }

    protected function firstValue()
    {
        return $this->stream->firstKey();
    }
}
