<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
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

    public function __construct(Pattern $pattern, string $subject, $nameOrIndex)
    {
        $this->limitAll = new GroupLimitAll($pattern, $subject, $nameOrIndex);
        $this->limitFirst = new GroupLimitFirst($pattern, $subject, $nameOrIndex);
        $this->offsetLimitFactory = new MatchOffsetLimitFactory($pattern, $subject, $nameOrIndex);
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
