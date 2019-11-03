<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid()
    {
        // given
        $pattern = Pattern::of('Foo');

        // when
        $valid = $pattern->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldNotBeValid()
    {
        // given
        $pattern = Pattern::of('invalid)');

        // when
        $valid = $pattern->valid();

        // then
        $this->assertFalse($valid);
    }
}
