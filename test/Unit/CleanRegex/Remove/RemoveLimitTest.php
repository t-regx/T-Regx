<?php
namespace Test\Unit\TRegx\CleanRegex\Remove;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Remove\RemoveLimit;

/**
 * @covers \TRegx\CleanRegex\Remove\RemoveLimit
 */
class RemoveLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRemoveFirst()
    {
        // given
        $limit = new RemoveLimit(Internal::pattern('\d+'), 'My ip 172.168.13.2 address');

        // when
        $actual = $limit->first();

        // then
        $this->assertSame('My ip .168.13.2 address', $actual);
    }

    /**
     * @test
     */
    public function shouldRemoveAll()
    {
        // given
        $limit = new RemoveLimit(Internal::pattern('\d+'), 'My ip 172.168.13.2 address');

        // when
        $actual = $limit->all();

        // then
        $this->assertSame('My ip ... address', $actual);
    }

    /**
     * @test
     */
    public function shouldRemoveOnly()
    {
        // given
        $limit = new RemoveLimit(Internal::pattern('\d+'), 'My ip 172.168.13.2 address');

        // when
        $actual = $limit->only(2);

        // then
        $this->assertSame('My ip ..13.2 address', $actual);
    }

    /**
     * @test
     */
    public function shouldThrowOnNegativeLimit()
    {
        // given
        $limit = new RemoveLimit(Internal::throw(), 'foo');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $limit->only(-2);
    }
}
