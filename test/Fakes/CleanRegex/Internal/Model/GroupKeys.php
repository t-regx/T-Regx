<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use Test\Utils\Fails;

class GroupKeys implements \TRegx\CleanRegex\Internal\Model\GroupKeys
{
    use Fails;

    /** @var array */
    private $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function getGroupKeys(): array
    {
        return $this->groupKeys;
    }
}
