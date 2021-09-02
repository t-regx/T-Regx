<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Nested;
use TRegx\CleanRegex\Internal\NonNestedValueException;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\SafeRegex\Internal\Tuple;

class GroupByPattern
{
    /** * @var Base */
    private $base;
    /** * @var GroupKey */
    private $group;

    public function __construct(Base $base, GroupKey $group)
    {
        $this->base = $base;
        $this->group = $group;
    }

    public function all(): array
    {
        return $this->groupBySimple(function (RawMatchesOffset $matches, int $index): string {
            return $matches->getTexts()[$index];
        });
    }

    public function offsets(): array
    {
        return $this->groupBySimple(function (RawMatchesOffset $matches, int $index): int {
            $offset = Tuple::second($matches->getGroupTextAndOffset(0, $index));
            return ByteOffset::toCharacterOffset($this->base->getSubject(), $offset);
        });
    }

    public function byteOffsets(): array
    {
        return $this->groupBySimple(function (RawMatchesOffset $matches, int $index): int {
            return Tuple::second($matches->getGroupTextAndOffset(0, $index));
        });
    }

    public function map(callable $mapper): array
    {
        return $this->groupBySimple(function (RawMatchesOffset $matches, int $index) use ($mapper) {
            return $mapper($this->detail($matches, $index));
        });
    }

    public function flatMap(callable $mapper): array
    {
        try {
            return $this->flattenMap($this->map($mapper), new ArrayMergeStrategy());
        } catch (NonNestedValueException $exception) {
            throw InvalidReturnValueException::forArrayReturning('flatMap', $exception->getType());
        }
    }

    public function flatMapAssoc(callable $mapper): array
    {
        try {
            return $this->flattenMap($this->map($mapper), new AssignStrategy());
        } catch (NonNestedValueException $exception) {
            throw InvalidReturnValueException::forArrayReturning('flatMapAssoc', $exception->getType());
        }
    }

    private function groupBySimple(callable $groupMapper): array
    {
        $matches = $this->base->matchAllOffsets();
        $map = [];
        foreach ($matches->getIndexes() as $index) {
            if ($matches->isGroupMatched($this->group->nameOrIndex(), $index)) {
                $key = Tuple::first($matches->getGroupTextAndOffset($this->group->nameOrIndex(), $index));
                $map[$key][] = $groupMapper($matches, $index);
            }
        }
        return $map;
    }

    private function flattenMap(array $groupped, FlatMapStrategy $strategy): array
    {
        foreach ($groupped as $groupKey => $grouppedValues) {
            $groupped[$groupKey] = $strategy->flatten(new Nested($grouppedValues));
        }
        return $groupped;
    }

    private function detail(RawMatchesOffset $matches, int $index): MatchDetail
    {
        return MatchDetail::create($this->base,
            $index,
            -1,
            new RawMatchesToMatchAdapter($matches, $index),
            new EagerMatchAllFactory($matches),
            $this->base->getUserData());
    }
}
