<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

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

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($this->all(), 0, $limit);
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->all() as $key => $value) {
            $consumer($value, $key);
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
}
