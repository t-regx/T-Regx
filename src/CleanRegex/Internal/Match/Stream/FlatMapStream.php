<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;

class FlatMapStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var FlatFunction */
    private $function;

    public function __construct(Upstream $upstream, FlatMapStrategy $strategy, FlatFunction $function)
    {
        $this->upstream = $upstream;
        $this->strategy = $strategy;
        $this->function = $function;
    }

    public function all(): array
    {
        return $this->strategy->flatten($this->function->map($this->upstream->all()));
    }

    public function first(): array
    {
        [$key, $value] = $this->upstream->first();
        return $this->firstArrayEntry($this->firstArrayOrRemainingArraysFlat($value));
    }

    private function firstArrayOrRemainingArraysFlat($value): array
    {
        $firstArray = $this->function->apply($value);
        if (empty($firstArray)) {
            return $this->remainingArraysFlat();
        }
        return $firstArray;
    }

    private function firstArrayEntry(array $array): array
    {
        $value = \reset($array);
        $key = \key($array);
        return [$this->strategy->firstKey($key), $value];
    }

    private function remainingArraysFlat(): array
    {
        $remainingFlat = $this->all();
        if (empty($remainingFlat)) {
            throw new EmptyStreamException();
        }
        return $remainingFlat;
    }
}
