<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class GroupKeys implements GroupAware
{
    use Fails;

    /** @var array */
    private $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function hasGroup(GroupKey $group): bool
    {
        throw $this->fail();
    }

    public function getGroupKeys(): array
    {
        return $this->groupKeys;
    }
}
