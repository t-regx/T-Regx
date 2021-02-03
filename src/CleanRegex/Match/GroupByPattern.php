<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\MapStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\OffsetsStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\Strategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\TextsStrategy;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class GroupByPattern
{
    /** * @var Base */
    private $base;
    /** * @var string|int */
    private $nameOrIndex;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function all(): array
    {
        return $this->groupBy(new TextsStrategy());
    }

    public function offsets(): array
    {
        return $this->groupBy(new OffsetsStrategy($this->base, true));
    }

    public function byteOffsets(): array
    {
        return $this->groupBy(new OffsetsStrategy(null, false));
    }

    public function map(callable $mapper): array
    {
        return $this->groupBy(new MapStrategy($mapper, $this->factory()));
    }

    public function flatMap(callable $mapper): array
    {
        return $this->groupBy(new FlatMapStrategy($mapper, new ArrayMergeStrategy(), $this->factory(), 'flatMap'));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        return $this->groupBy(new FlatMapStrategy($mapper, new AssignStrategy(), $this->factory(), 'flatMapAssoc'));
    }

    private function factory(): DetailObjectFactory
    {
        return new DetailObjectFactory($this->base, -1, $this->base->getUserData());
    }

    private function groupBy(Strategy $strategy): array
    {
        $matches = $this->base->matchAllOffsets();
        return $strategy->transform($this->groupMatches($matches), $matches);
    }

    private function groupMatches(RawMatchesOffset $matches): array
    {
        $map = [];
        foreach ($matches->getTexts() as $i => $text) {
            if (!$matches->isGroupMatched($this->nameOrIndex, $i)) {
                continue;
            }
            $key = $matches->getGroupTextAndOffset($this->nameOrIndex, $i)[0];
            $map[$key][] = $matches->getIndexedRawMatchOffset($i);
        }
        return $map;
    }
}
