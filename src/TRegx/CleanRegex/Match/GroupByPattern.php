<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\GroupBy\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\MapStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\OffsetsStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\Strategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\TextsStrategy;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;

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

    public function texts(): array
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
        return $this->groupBy(new FlatMapStrategy($mapper, $this->factory()));
    }

    private function factory(): MatchObjectFactory
    {
        return new MatchObjectFactory($this->base, -1, $this->base->getUserData());
    }

    private function groupBy(Strategy $strategy): array
    {
        $matches = $this->base->matchAllOffsets();
        return $strategy->transform($this->groupMatches($matches), $matches);
    }

    private function groupMatches(IRawMatchesOffset $matches): array
    {
        $map = [];
        for ($i = 0; $i < \count($matches->getTexts()); ++$i) {
            if ($matches->isGroupMatched($this->nameOrIndex, $i)) {
                $key = $matches->getGroupTextAndOffset($this->nameOrIndex, $i)[0];
                $map[$key][] = $matches->getIndexedRawMatchOffset($i);
            }
        }
        return $map;
    }
}
