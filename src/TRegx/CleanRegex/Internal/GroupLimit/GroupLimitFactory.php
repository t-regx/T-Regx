<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitFactory
{
    /** @var GroupLimitAll */
    private $limitAll;
    /** @var GroupLimitFirst */
    private $limitFirst;

    public function __construct(Pattern $pattern, string $subject, $nameOrIndex)
    {
        $this->limitAll = new GroupLimitAll($pattern, $subject, $nameOrIndex);
        $this->limitFirst = new GroupLimitFirst($pattern, $subject, $nameOrIndex);
    }

    public function create(): GroupLimit
    {
        return new GroupLimit(
            function () {
                return $this->limitAll->getAllForGroup();
            },
            function () {
                return $this->limitFirst->getFirstForGroup();
            });
    }
}
