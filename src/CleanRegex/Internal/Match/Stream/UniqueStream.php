<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class UniqueStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $stream;

    public function __construct(Upstream $stream)
    {
        $this->stream = $stream;
    }

    public function all(): array
    {
        return \array_unique($this->stream->all());
    }

    public function first()
    {
        return $this->stream->first();
    }
}
