<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class MapStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $stream;
    /** @var callable */
    private $mapFunction;

    public function __construct(Upstream $stream, callable $mapFunction)
    {
        $this->stream = $stream;
        $this->mapFunction = $mapFunction;
    }

    public function all(): array
    {
        return \array_map($this->mapFunction, $this->stream->all());
    }

    public function first()
    {
        return ($this->mapFunction)($this->stream->first());
    }
}
