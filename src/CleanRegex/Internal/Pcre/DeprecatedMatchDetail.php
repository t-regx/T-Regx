<?php
namespace TRegx\CleanRegex\Internal\Pcre;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Pcre\Legacy\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Signatures\PerformanceSignatures;
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
    public static function create(Subject              $subject, int $index, int $limit,
                                  IRawMatchOffset      $match, MatchAllFactory $allFactory,
                                  GroupFactoryStrategy $strategy = null): MatchDetail
    {
        return new MatchDetail($subject, $index, $limit, $match, $match, $match, $match, $allFactory,
            $strategy ?? new MatchGroupFactoryStrategy(),
            new PerformanceSignatures($match, $match));
    }
}
