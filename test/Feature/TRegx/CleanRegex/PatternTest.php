<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function should_matches()
    {
        // when
        $matches = pattern('\d')->matches('abc');

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function should_fails()
    {
        // when
        $matches = pattern('\d')->fails('abc');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function should_count()
    {
        // when
        $count = pattern('\d+')->count('111-222-333');

        // then
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     */
    public function should_count_unmatched()
    {
        // when
        $count = pattern('[a-z]+')->count('111-222-333');

        // then
        $this->assertEquals(0, $count);
    }

    /**
     * @test
     */
    public function should_quote()
    {
        // when
        $quoted = pattern('[a-z]+')->quote();

        // then
        $this->assertEquals('\[a\-z\]\+', $quoted);
    }

    /**
     * @test
     */
    public function shouldFilterArray()
    {
        // given
        $array = [
            'Uppercase',
            'lowercase',
            'Uppercase again',
            'lowercase again',
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->filter($array);

        // then
        $expected = ['Uppercase', 'Uppercase again'];
        $this->assertEquals($expected, $result);
    }
}
