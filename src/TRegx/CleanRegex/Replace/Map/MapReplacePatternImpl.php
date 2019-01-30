<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Replace\Map\Exception\GroupMessageExceptionStrategy;
use TRegx\CleanRegex\Replace\Map\Exception\MatchMessageExceptionStrategy;
use TRegx\CleanRegex\Replace\Map\Exception\MissingReplacementExceptionMessageStrategy;

class MapReplacePatternImpl implements MapReplacePattern
{
    /** @var MapReplacer */
    private $mapReplacer;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MissingReplacementExceptionMessageStrategy */
    private $strategy;

    public function __construct(MapReplacer $mapReplacer, $nameOrIndex, MissingReplacementExceptionMessageStrategy $strategy = null)
    {
        $this->mapReplacer = $mapReplacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->strategy = $strategy ?? new MatchMessageExceptionStrategy();
    }

    public function group($nameOrIndex): MapGroupReplacePattern
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return new MapReplacePatternImpl($this->mapReplacer, $nameOrIndex, new GroupMessageExceptionStrategy());
    }

    public function map(array $map): string
    {
        return $this->mapOrCallHandler($map, function (string $occurrence, string $group) {
            throw $this->strategy->create($occurrence, $this->nameOrIndex, $group);
        });
    }

    public function mapIfExists(array $map): string
    {
        return $this->mapOrCallHandler($map, function (string $occurrence) {
            return $occurrence;
        });
    }

    public function mapOrDefault(array $map, string $defaultReplacement): string
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
