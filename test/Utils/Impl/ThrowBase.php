<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Number\Base;

class ThrowBase extends Base
{
    public function __construct()
    {
        parent::__construct(2);
    }

    public function base(): int
    {
        throw new AssertionError("Failed to assert that number Base wasn't used");
    }
}
