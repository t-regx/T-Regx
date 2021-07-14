<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\exactly;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIgnore_first_exactly_once()
    {
        // when
        $result = pattern('Foo')->replace('Foo Bar Bar Bar')->first()->exactly()->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldIgnore_two_exactly_twice()
    {
        // when
        $result = pattern('Foo')->replace('Foo Foo Bar Bar')->only(2)->exactly()->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_first_exactly_none()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but 0 replacement(s) were actually performed');

        // when
        pattern('Foo')->replace('Bar Bar Bar Bar')->first()->exactly()->with('Bar');
    }

    /**
     * @test
     */
    public function shouldThrow_two_exactly_once()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but 1 replacement(s) were actually performed');

        // when
        pattern('Foo')->replace('Foo Bar Bar Bar')->only(2)->exactly()->with('Bar');
    }

    /**
     * @test
     */
    public function shouldThrow_first_exactly_twice()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but at least 2 replacement(s) would have been performed');

        // when
        pattern('Foo')->replace('Foo Foo Bar Bar')->first()->exactly()->with('Bar');
    }

    /**
     * @test
     */
    public function shouldThrow_two_exactly_thrice()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but at least 3 replacement(s) would have been performed');

        // when
        pattern('Foo')->replace('Foo Foo Foo Bar')->only(2)->exactly()->with('Bar');
    }
}
