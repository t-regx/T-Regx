<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Optional;

trait AssertsOptionalPresent
{
    public function assertOptionalPresent(Optional $optional, $expected): void
    {
        Assert::assertSame($expected, $optional->orElse(Functions::assertArgumentless()));
        Assert::assertSame($expected, $optional->orReturn('Foo'));
        Assert::assertSame($expected, $optional->orThrow(new \Exception()));
        Assert::assertSame($expected, $optional->get());
    }
}
