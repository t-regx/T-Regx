<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class MapEntriesStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var callable */
    private $mapFunction;

    public function __construct(Upstream $upstream, callable $mapFunction)
    {
        $this->upstream = $upstream;
        $this->mapFunction = $mapFunction;
    }

    public function all(): array
    {
        return $this->mappedEntries($this->upstream->all());
    }

    private function mappedEntries(array $array): array
    {
        $keys = \array_keys($array);
        return \array_combine($keys, \array_map($this->mapFunction, $keys, $array));
    }

    public function first(): array
    {
        [$key, $value] = $this->upstream->first();
        return [$key, ($this->mapFunction)($key, $value)];
    }
}
