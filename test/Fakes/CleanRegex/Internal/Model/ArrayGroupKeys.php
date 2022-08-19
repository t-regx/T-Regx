<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Model\GroupKeys;

class ArrayGroupKeys implements GroupKeys
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
