<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

trait MatchPatternHelpers
{
    abstract public function findFirst(callable $consumer): Optional;

    public function tuple($nameOrIndex1, $nameOrIndex2): array
    {
        return $this
            ->findFirst(function (Match $match) use ($nameOrIndex1, $nameOrIndex2) {
                return [
                    $match->group($nameOrIndex1)->orReturn(null),
                    $match->group($nameOrIndex2)->orReturn(null),
                ];
            })
            ->orElse(function (NotMatched $notMatched) use ($nameOrIndex1, $nameOrIndex2) {
                $this->validateGroups($notMatched, [$nameOrIndex1, $nameOrIndex2]);
                throw SubjectNotMatchedException::forFirstTuple(
                    new Subject($notMatched->subject()),
                    $nameOrIndex1,
                    $nameOrIndex2);
            });
    }

    public function triple($nameOrIndex1, $nameOrIndex2, $nameOrIndex3): array
    {
        return $this
            ->findFirst(function (Match $match) use ($nameOrIndex1, $nameOrIndex2, $nameOrIndex3) {
                return [
                    $match->group($nameOrIndex1)->orReturn(null),
                    $match->group($nameOrIndex2)->orReturn(null),
                    $match->group($nameOrIndex3)->orReturn(null),
                ];
            })
            ->orElse(function (NotMatched $notMatched) use ($nameOrIndex1, $nameOrIndex2, $nameOrIndex3) {
                $this->validateGroups($notMatched, [$nameOrIndex1, $nameOrIndex2, $nameOrIndex3]);
                throw SubjectNotMatchedException::forFirstTriple(
                    new Subject($notMatched->subject()),
                    $nameOrIndex1,
                    $nameOrIndex2,
                    $nameOrIndex3);
            });
    }

    private function validateGroups(NotMatched $notMatched, array $groups): void
    {
        foreach ($groups as $group) {
            if (!$notMatched->hasGroup($group)) {
                throw new NonexistentGroupException($group);
            }
        }
    }
}
