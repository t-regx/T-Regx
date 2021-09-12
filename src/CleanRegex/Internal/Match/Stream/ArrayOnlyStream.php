<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class ArrayOnlyStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $stream;
    /** @var callable */
    private $mapper;

    public function __construct(Upstream $stream, callable $mapper)
    {
        $this->stream = $stream;
        $this->mapper = $mapper;
    }

    public function all(): array
    {
        return ($this->mapper)($this->stream->all());
    }

    public function first()
    {
        return $this->stream->first();
    }
}
