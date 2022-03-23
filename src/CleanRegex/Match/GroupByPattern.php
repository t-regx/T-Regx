<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Nested;
use TRegx\CleanRegex\Internal\NonNestedValueException;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\SafeRegex\Internal\Tuple;

class GroupByPattern
{
    /** * @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var GroupHasAware */
    private $groupAware;
    /** * @var GroupKey */
    private $group;
    /** @var UserData */
    private $userData;

    public function __construct(Base $base, Subject $subject, UserData $userData, GroupHasAware $groupAware, GroupKey $group)
    {
        $this->base = $base;
        $this->subject = $subject;
        $this->groupAware = $groupAware;
        $this->group = $group;
        $this->userData = $userData;
    }

    public function all(): array
    {
        return $this->groupBySimple(static function (RawMatchesOffset $matches, int $index): string {
            return $matches->getTexts()[$index];
        });
    }

    public function offsets(): array
    {
        return $this->groupBySimple(function (RawMatchesOffset $matches, int $index): int {
            $offset = Tuple::second($matches->getGroupTextAndOffset(0, $index));
            return ByteOffset::toCharacterOffset($this->subject, $offset);
        });
    }

    public function byteOffsets(): array
    {
        return $this->groupBySimple(static function (RawMatchesOffset $matches, int $index): int {
            return Tuple::second($matches->getGroupTextAndOffset(0, $index));
        });
    }

    public function map(callable $mapper): array
    {
        return $this->groupBySimple(function (RawMatchesOffset $matches, int $index) use ($mapper) {
            return $mapper($this->detail($matches, $index));
        });
    }

    private function groupBySimple(callable $groupMapper): array
    {
        $matches = $this->base->matchAllOffsets();
        $map = [];
        if (!$this->groupAware->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        foreach ($matches->getIndexes() as $index) {
            if ($matches->isGroupMatched($this->group->nameOrIndex(), $index)) {
                $key = Tuple::first($matches->getGroupTextAndOffset($this->group->nameOrIndex(), $index));
                $map[$key][] = $groupMapper($matches, $index);
            }
        }
        return $map;
    }

    public function flatMap(callable $mapper): array
    {
        try {
            return $this->flattenMap($this->map($mapper), new ArrayMergeStrategy());
        } catch (NonNestedValueException $exception) {
            throw new InvalidReturnValueException('flatMap', 'array', $exception->getType());
        }
    }

    public function flatMapAssoc(callable $mapper): array
    {
        try {
            return $this->flattenMap($this->map($mapper), new AssignStrategy());
        } catch (NonNestedValueException $exception) {
            throw new InvalidReturnValueException('flatMapAssoc', 'array', $exception->getType());
        }
    }

    private function flattenMap(array $grouped, FlatMapStrategy $strategy): array
    {
        $flattened = [];
        foreach ($grouped as $groupKey => $groupedValues) {
            $flattened[$groupKey] = $strategy->flatten(new Nested($groupedValues));
        }
        return $flattened;
    }

    private function detail(RawMatchesOffset $matches, int $index): MatchDetail
    {
        return DeprecatedMatchDetail::create($this->subject,
            $index,
            -1,
            new RawMatchesToMatchAdapter($matches, $index),
            new EagerMatchAllFactory($matches),
            $this->userData);
    }
}
