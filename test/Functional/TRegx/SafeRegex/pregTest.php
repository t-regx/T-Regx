<?php
namespace Test\Functional\TRegx\SafeRegex;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_replace()
    {
        // when
        $result = preg::replace([], [], []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_filter()
    {
        // when
        $result = preg::filter([], [], []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_arrayInput_filteredOut()
    {
        // when
        $result = preg::filter('/c/', '', ['a', 'b']);

        // then
        $this->assertEquals([], $result);
    }
}
