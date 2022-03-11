<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

/**
 * The name {@see ListStream} is supposed to mean a stream
 * that behaves as if it was returning a list, an ordered
 * collection with consecutive indexes, starting at 0.
 *
 * It has implementation details {@see ListStream::entries} and
 * {@see ListStream::firstValue}, which are supposed to receive
 * values as if from a dictionary.
 *
 * So {@see ListStream::all()} returns a sequential array,
 * and {@see ListStream::firstKey()} returns 0.
 */
trait ListStream
{
    public function all(): array
    {
        return \array_values($this->entries());
    }

    public function first()
    {
        return $this->firstValue();
    }

    public function firstKey(): int
    {
        $this->firstValue();
        return 0;
    }

    protected abstract function entries(): array;

    protected abstract function firstValue();
}
