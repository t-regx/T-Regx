<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\RawMatchAsArray;
use TRegx\CleanRegex\Match\Details\LazyRawWithGroups;
use TRegx\SafeRegex\preg;

class AsArrayStream implements Stream
{
    /** @var BaseStream */
    private $stream;
    /** @var Base */
    private $base;

    public function __construct(BaseStream $stream, Base $base)
    {
        $this->stream = $stream;
        $this->base = $base;
    }

    /**
     * @return array[]
     */
    public function all(): array
    {
        // Not yet making RawMatchesSetOrder for this "raw" usage
        preg::match_all($this->base->getPattern()->pattern, $this->base->getSubject(), $matches, PREG_OFFSET_CAPTURE);
        return $this->groupByIndex($matches);
    }

    /**
     * @return string[]
     */
    public function first(): array
    {
        return RawMatchAsArray::fromMatch($this->stream->first(), new LazyRawWithGroups($this->base));
    }

    public function firstKey()
    {
        return $this->stream->firstKey();
    }

    private function groupByIndex(array $matches): array
    {
        $result = [];
        foreach ($matches as $group => $match) {
            foreach ($matches[$group] as $index => $value) {
                $result[$index][$group] = $value === '' || $value[1] === -1 ? null : $value[0];
            }
        }
        return $result;
    }
}
