<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

class MatchOffsetLimitFactory
{
    /** @var MatchOffsetLimitAll */
    private $limitAll;
    /** @var MatchOffsetLimitFirst */
    private $limitFirst;

    public function __construct(Pattern $pattern, string $subject, $nameOrIndex)
    {
        $this->limitAll = new MatchOffsetLimitAll($pattern, $subject, $nameOrIndex);
        $this->limitFirst = new MatchOffsetLimitFirst($pattern, $subject, $nameOrIndex);
    }

    public function create(): MatchOffsetLimit
    {
        return new MatchOffsetLimit(
            function (int $limit, bool $allowNegative) {
                return $this->limitAll->getAllForGroup($limit, $allowNegative);
            },
            function () {
                return $this->limitFirst->getFirstForGroup();
            }
        );
    }
}
