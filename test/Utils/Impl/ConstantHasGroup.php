<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class ConstantHasGroup implements GroupHasAware
{
    /** @var bool */
    private $hasGroup;

    public function __construct(bool $hasGroup)
    {
        $this->hasGroup = $hasGroup;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return $this->hasGroup;
    }
}
