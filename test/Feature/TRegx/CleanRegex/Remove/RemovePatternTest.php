<?php
namespace Test\Feature\TRegx\CleanRegex\Remove;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RemovePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRemoveAll()
    {
        // when
        $result = pattern('\d+')->remove('My ip is 192.168.2.14')->all();

        // then
        $this->assertEquals('My ip is ...', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveFirst()
    {
        // when
        $result = pattern('\d+')->remove('My ip is 192.168.2.14')->first();

        // then
        $this->assertEquals('My ip is .168.2.14', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveOnly()
    {
        // when
        $result = pattern('\d+')->remove('My ip is 192.168.2.14')->only(3);

        // then
        $this->assertEquals('My ip is ...14', $result);
    }

    /**
     * @test
     */
    public function shouldThrowOnNegativeIndex()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit -2');

        // when
        pattern('\d+')->remove('My ip is 192.168.2.14')->only(-2);
    }
}
