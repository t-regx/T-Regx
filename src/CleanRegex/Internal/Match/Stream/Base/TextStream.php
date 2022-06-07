<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

class TextStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;

    public function __construct(StreamBase $stream)
    {
        $this->stream = $stream;
    }

    protected function entries(): array
    {
        return $this->stream->all()->getTexts();
    }

    protected function firstValue(): string
    {
        return $this->stream->first()->getText();
    }
}
