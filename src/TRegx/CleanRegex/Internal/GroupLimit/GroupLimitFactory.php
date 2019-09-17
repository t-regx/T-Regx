<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitFactory
{
    /** @var Base */
    private $base;
    /** @var GroupLimitAll */
    private $limitAll;
    /** @var GroupLimitFirst */
    private $limitFirst;
    /** @var  MatchOffsetLimitFactory */
    private $offsetLimitFactory;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Base $base, $nameOrIndex, bool $isWholeMatch)
    {
        $this->base = $base;
        $this->limitAll = new GroupLimitAll($base, $nameOrIndex);
        $this->limitFirst = new GroupLimitFirst($base, $nameOrIndex);
        $this->offsetLimitFactory = new MatchOffsetLimitFactory($base, $nameOrIndex, $isWholeMatch);
        $this->nameOrIndex = $nameOrIndex;
    }

    public function create(): GroupLimit
    {
        return new GroupLimit(
            function () {
                return $this->limitAll->getAllForGroup();
            },
            function () {
                return $this->limitFirst->getFirstForGroup();
            },
            $this->offsetLimitFactory,
            $this->base,
            $this->nameOrIndex
        );
    }
}
