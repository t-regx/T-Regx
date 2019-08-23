<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

class MatchOffsetLimitFactory
{
    /** @var MatchOffsetLimitAll */
    private $limitAll;
    /** @var MatchOffsetLimitFirst */
    private $limitFirst;

    public function __construct(Base $base, $nameOrIndex, bool $isWholeMatch)
    {
        $this->limitAll = new MatchOffsetLimitAll($base, $nameOrIndex);
        $this->limitFirst = new MatchOffsetLimitFirst($base, $nameOrIndex, $isWholeMatch);
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
