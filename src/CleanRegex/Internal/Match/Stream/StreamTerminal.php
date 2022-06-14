<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;

class StreamTerminal
{
    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
    }

    public function all(): array
    {
        try {
            return $this->upstream->all();
        } catch (UnmatchedStreamException $exception) {
            return [];
        }
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->all() as $value) {
            $consumer($value);
        }
    }

    public function count(): int
    {
        return \count($this->all());
    }

    public function getIterator(): \Iterator
    {
        try {
            return new \ArrayIterator($this->upstream->all());
        } catch (UnmatchedStreamException $exception) {
            return new \EmptyIterator();
        }
    }

    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this->all() as $detail) {
            $accumulator = $reducer($accumulator, $detail);
        }
        return $accumulator;
    }
}
