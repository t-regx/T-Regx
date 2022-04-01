<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Match\GroupBy\ArrayFunction;
use TRegx\CleanRegex\Internal\Match\GroupBy\ByteOffsetFunction;
use TRegx\CleanRegex\Internal\Match\GroupBy\CallableFunction;
use TRegx\CleanRegex\Internal\Match\GroupBy\DetailFunction;
use TRegx\CleanRegex\Internal\Match\GroupBy\OffsetFunction;
use TRegx\CleanRegex\Internal\Match\GroupBy\TextFunction;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Nested;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
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
        return $this->groupedByFunction(new TextFunction());
    }

    public function offsets(): array
    {
        return $this->groupedByFunction(new OffsetFunction());
    }

    public function byteOffsets(): array
    {
        return $this->groupedByFunction(new ByteOffsetFunction());
    }

    public function map(callable $mapper): array
    {
        return $this->groupedByFunction(new CallableFunction($mapper));
    }

    public function flatMap(callable $mapper): array
    {
        return $this->flattenedMap($this->groupedByFunction(new ArrayFunction($mapper, 'flatMap')), new ArrayMergeStrategy());
    }

    public function flatMapAssoc(callable $mapper): array
    {
        return $this->flattenedMap($this->groupedByFunction(new ArrayFunction($mapper, 'flatMapAssoc')), new AssignStrategy());
    }

    private function groupedByFunction(DetailFunction $function): array
    {
        if (!$this->groupAware->hasGroup($this->group)) {
            throw new NonexistentGroupException($this->group);
        }
        $map = [];
        $matches = $this->base->matchAllOffsets();
        foreach ($matches->getIndexes() as $index) {
            if ($matches->isGroupMatched($this->group->nameOrIndex(), $index)) {
                $key = Tuple::first($matches->getGroupTextAndOffset($this->group->nameOrIndex(), $index));
                $detail = $this->detail($matches, $index);
                $map[$key][] = $function->apply($detail);
            }
        }
        return $map;
    }

    private function flattenedMap(array $grouped, FlatMapStrategy $strategy): array
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
