<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class SkipStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var int */
    private $offset;

    public function __construct(Upstream $upstream, int $offset)
    {
        $this->upstream = $upstream;
        $this->offset = $offset;
    }

    public function all(): array
    {
        return \array_slice($this->upstream->all(), $this->offset, null, true);
    }

    public function first(): array
    {
        if ($this->offset === 0) {
            return $this->upstream->first();
        }
        $all = $this->upstream->all();
        if (\count($all) > $this->offset) {
            return $this->firstEntry($all);
        }
        throw new EmptyStreamException();
    }

    private function firstEntry(array $elements): array
    {
        $singletonArray = \array_slice($elements, $this->offset, 1, true);
        return [\key($singletonArray), \current($singletonArray)];
    }
}
