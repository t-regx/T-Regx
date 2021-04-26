<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;

trait TestCasePasses
{
    public function pass()
    {
        Assert::assertTrue(true);
    }
}
