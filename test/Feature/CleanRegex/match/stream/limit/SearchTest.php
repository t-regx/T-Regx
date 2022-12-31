<?php
namespace Test\Feature\CleanRegex\match\stream\limit;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowForInvalidPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->search('Foo')->stream()->limit(2)->all();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternFirst()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->search('Foo')->stream()->limit(2)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternLimitZero()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->search('Foo')->stream()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternFirstKeyLimitZero()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->search('Foo')->stream()->limit(0)->keys()->first();
    }
}
