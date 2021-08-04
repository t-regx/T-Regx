<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class TextStream implements Stream
{
    use PreservesKey;

    /** @var StreamBase */
    private $stream;

    public function __construct(StreamBase $stream)
    {
        $this->stream = $stream;
    }

    public function all(): array
    {
        return $this->stream->all()->getTexts();
    }

    public function first(): string
    {
        return $this->stream->first()->getText();
    }
}
