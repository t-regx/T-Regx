<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Replace\Exception\MissingReplacementKeyException;

class MapReplacePatternImpl implements MapReplacePattern
{
    /** @var MapReplacer */
    private $mapReplacer;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(MapReplacer $mapReplacer, $nameOrIndex)
    {
        $this->mapReplacer = $mapReplacer;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function group($nameOrIndex): MapGroupReplacePattern
    {
        return new MapReplacePatternImpl($this->mapReplacer, $nameOrIndex);
    }

    public function map(array $map): string
    {
        return $this->mapOrCallHandler($map, function (string $occurrence) {
            throw MissingReplacementKeyException::create($occurrence);
        });
    }

    public function mapIfExists(array $map): string
    {
        return $this->mapOrCallHandler($map, function (string $occurrence) {
            return $occurrence;
        });
    }

    public function mapDefault(array $map, string $defaultReplacement): string
    {
        return $this->mapOrCallHandler($map, function () use ($defaultReplacement) {
            return $defaultReplacement;
        });
    }

    private function mapOrCallHandler(array $map, callable $unexpectedReplacementHandler): string
    {
        return $this->mapReplacer->mapOrCallHandler($this->nameOrIndex, $map, $unexpectedReplacementHandler);
    }
}
