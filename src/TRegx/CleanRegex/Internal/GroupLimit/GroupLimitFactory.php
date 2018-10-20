<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitFactory
{
    /** @var GroupLimitAll */
    private $limitAll;
    /** @var GroupLimitFirst */
    private $limitFirst;
    /** @var  MatchOffsetLimitFactory */
    private $offsetLimitFactory;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->limitAll = new GroupLimitAll($base, $nameOrIndex);
        $this->limitFirst = new GroupLimitFirst($base, $nameOrIndex);
        $this->offsetLimitFactory = new MatchOffsetLimitFactory($base, $nameOrIndex);
    }

    public function create(): GroupLimit
    {
        return new GroupLimit(
            function (int $limit, bool $allowNegative) {
                return $this->limitAll->getAllForGroup($limit, $allowNegative);
            },
            function () {
                return $this->limitFirst->getFirstForGroup();
            },
            $this->offsetLimitFactory
        );
    }
}
