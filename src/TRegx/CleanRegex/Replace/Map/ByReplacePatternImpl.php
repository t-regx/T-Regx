<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Replace\NonReplaced\Map\Exception\GroupMessageExceptionStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\Map\Exception\MatchMessageExceptionStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\Map\Exception\MissingReplacementExceptionMessageStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\MapReplaceStrategy;

class ByReplacePatternImpl implements ByReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MissingReplacementExceptionMessageStrategy */
    private $messageStrategy;

    public function __construct(GroupFallbackReplacer $mapReplacer, $nameOrIndex, MissingReplacementExceptionMessageStrategy $messageStrategy = null)
    {
        $this->fallbackReplacer = $mapReplacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->messageStrategy = $messageStrategy ?? new MatchMessageExceptionStrategy();
    }

    public function group($nameOrIndex): ByGroupReplacePattern
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return new ByReplacePatternImpl($this->fallbackReplacer, $nameOrIndex, new GroupMessageExceptionStrategy());
    }

    public function map(array $map): string
    {
        return $this->mapOrCallHandler($map, function (string $occurrence, string $group) {
            throw $this->messageStrategy->create($occurrence, $this->nameOrIndex, $group);
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
        return $this->fallbackReplacer->replaceOrFallback(
            $this->nameOrIndex,
            new MapReplaceStrategy($map),
            $unexpectedReplacementHandler);
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class)
    {
        return '';
    }

    public function orReturn($default)
    {
        return '';
    }

    public function orElse(callable $producer)
    {
        return '';
    }
}
