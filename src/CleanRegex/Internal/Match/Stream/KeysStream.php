<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class KeysStream implements Upstream
{
    use ListStream;

    /** @var Upstream */
    private $stream;

    public function __construct(Upstream $stream)
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
