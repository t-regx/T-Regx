<?php
namespace TRegx\CleanRegex\Internal\Pcre;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\MatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\Prime;
use TRegx\CleanRegex\Internal\Pcre\Signatures\PerformanceSignatures;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @deprecated
 */
class DeprecatedMatchDetail
{
    /**
     * @deprecated
     */
    public static function create(Subject              $subject,
                                  int                  $index,
                                  IRawMatchOffset      $match,
                                  MatchAllFactory      $allFactory,
                                  Prime                $prime,
                                  GroupFactoryStrategy $strategy = null): MatchDetail
    {
        return new MatchDetail($subject, $index, $match, $match, $match, $allFactory,
            $strategy ?? new MatchGroupFactoryStrategy(),
            new PerformanceSignatures($match, $match),
            $prime);
    }
}
