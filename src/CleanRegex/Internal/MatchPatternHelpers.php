<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchTripleMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchTupleMessage;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Optional;

trait MatchPatternHelpers
{
    abstract public function findFirst(callable $consumer): Optional;

    abstract public function subject(): string;

    public function tuple($nameOrIndex1, $nameOrIndex2): array
    {
        GroupKey::of($nameOrIndex1);
        GroupKey::of($nameOrIndex2);
        return $this
            ->findFirst(static function (Detail $detail) use ($nameOrIndex1, $nameOrIndex2) {
                $first = $detail->group($nameOrIndex1);
                $second = $detail->group($nameOrIndex2);
                return [
                    $first->matched() ? $first->text() : null,
                    $second->matched() ? $second->text() : null,
                ];
            })
            ->orElse(function () use ($nameOrIndex1, $nameOrIndex2) {
                self::validateGroups([$nameOrIndex1, $nameOrIndex2]);
                throw new SubjectNotMatchedException(new FromFirstMatchTupleMessage(
                    GroupKey::of($nameOrIndex1),
                    GroupKey::of($nameOrIndex2)),
                    new Subject($this->subject()));
            });
    }

    public function triple($nameOrIndex1, $nameOrIndex2, $nameOrIndex3): array
    {
        GroupKey::of($nameOrIndex1);
        GroupKey::of($nameOrIndex2);
        GroupKey::of($nameOrIndex3);
        return $this
            ->findFirst(static function (Detail $detail) use ($nameOrIndex1, $nameOrIndex2, $nameOrIndex3) {
                $first = $detail->group($nameOrIndex1);
                $second = $detail->group($nameOrIndex2);
                $third = $detail->group($nameOrIndex3);
                return [
                    $first->matched() ? $first->text() : null,
                    $second->matched() ? $second->text() : null,
                    $third->matched() ? $third->text() : null
                ];
            })
            ->orElse(function () use ($nameOrIndex1, $nameOrIndex2, $nameOrIndex3) {
                $this->validateGroups([$nameOrIndex1, $nameOrIndex2, $nameOrIndex3]);
                throw new SubjectNotMatchedException(new FromFirstMatchTripleMessage(
                    GroupKey::of($nameOrIndex1),
                    GroupKey::of($nameOrIndex2),
                    GroupKey::of($nameOrIndex3)),
                    new Subject($this->subject()));
            });
    }

    private function validateGroups(array $groups): void
    {
        foreach ($groups as $group) {
            if (!$this->groupExists($group)) {
                throw new NonexistentGroupException(GroupKey::of($group));
            }
        }
    }
}
