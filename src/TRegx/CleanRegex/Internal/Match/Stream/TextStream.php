<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class TextStream implements Stream
{
    /** @var BaseStream */
    private $stream;

    public function __construct(BaseStream $stream)
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

    public function firstKey(): int
    {
        return $this->stream->firstKey();
    }
}
