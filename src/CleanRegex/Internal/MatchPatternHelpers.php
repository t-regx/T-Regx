<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchTripleMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchTupleMessage;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

trait MatchPatternHelpers
{
    abstract public function findFirst(callable $consumer): Optional;

    public function tuple($nameOrIndex1, $nameOrIndex2): array
    {
        return $this
            ->findFirst(static function (Detail $detail) use ($nameOrIndex1, $nameOrIndex2) {
                return [
                    $detail->group($nameOrIndex1)->orReturn(null),
                    $detail->group($nameOrIndex2)->orReturn(null),
                ];
            })
            ->orElse(static function (NotMatched $notMatched) use ($nameOrIndex1, $nameOrIndex2) {
                self::validateGroups($notMatched, [$nameOrIndex1, $nameOrIndex2]);
                throw new SubjectNotMatchedException(new FromFirstMatchTupleMessage(
                    GroupKey::of($nameOrIndex1),
                    GroupKey::of($nameOrIndex2)),
                    new Subject($notMatched->subject()));
            });
    }

    public function triple($nameOrIndex1, $nameOrIndex2, $nameOrIndex3): array
    {
        return $this
            ->findFirst(static function (Detail $detail) use ($nameOrIndex1, $nameOrIndex2, $nameOrIndex3) {
                return [
                    $detail->group($nameOrIndex1)->orReturn(null),
                    $detail->group($nameOrIndex2)->orReturn(null),
                    $detail->group($nameOrIndex3)->orReturn(null),
                ];
            })
            ->orElse(static function (NotMatched $notMatched) use ($nameOrIndex1, $nameOrIndex2, $nameOrIndex3) {
                self::validateGroups($notMatched, [$nameOrIndex1, $nameOrIndex2, $nameOrIndex3]);
                throw new SubjectNotMatchedException(new FromFirstMatchTripleMessage(
                    GroupKey::of($nameOrIndex1),
                    GroupKey::of($nameOrIndex2),
                    GroupKey::of($nameOrIndex3)),
                    new Subject($notMatched->subject()));
            });
    }

    private static function validateGroups(NotMatched $notMatched, array $groups): void
    {
        foreach ($groups as $group) {
            if (!$notMatched->hasGroup($group)) {
                throw new NonexistentGroupException(GroupKey::of($group));
            }
        }
    }
}
