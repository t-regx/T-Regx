<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class IgnoreAware implements GroupHasAware
{
    public function hasGroup($nameOrIndex): bool
    {
        return true;
    }
}
