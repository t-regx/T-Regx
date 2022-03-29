<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\MatchDetail;

/**
 * @deprecated
 */
class DeprecatedMatchDetail
{
    /**
     * @deprecated
     */
    public static function create(Subject         $subject, int $index, int $limit,
                                  IRawMatchOffset $match, MatchAllFactory $allFactory,
                                  UserData        $userData, GroupFactoryStrategy $strategy = null): MatchDetail
    {
        return new MatchDetail($subject, $index, $limit, $match, $match, $match, $match, $allFactory, $userData,
            $strategy ?? new MatchGroupFactoryStrategy(),
            new PerformanceSignatures($match, $match));
    }
}