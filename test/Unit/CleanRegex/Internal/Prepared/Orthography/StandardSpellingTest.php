<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Orthography;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\EqualsCondition;
use Test\Utils\Impl\ThrowCondition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling
 */
class StandardSpellingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiter()
    {
        // given
        $standard = new StandardSpelling('#wel/come%', '', new EqualsCondition('#'));

        // when
        $delimiter = $standard->delimiter();

        // then
        $this->assertEquals(new Delimiter('#'), $delimiter);
    }

    /**
     * @test
     */
    public function shouldGetInputPattern()
    {
        // given
        $standard = new StandardSpelling('#wel/{come}', '', new ThrowCondition());

        // when
        $pattern = $standard->pattern();

        // then
        $this->assertSame('#wel/{come}', $pattern);
    }

    /**
     * @test
     */
    public function shouldGetUndevelopedInput()
    {
        // given
        $standard = new StandardSpelling('#wel/{come}', '', new ThrowCondition());

        // when
        $undeveloped = $standard->undevelopedInput();

        // then
        $this->assertSame('#wel/{come}', $undeveloped);
    }

    /**
     * @test
     */
    public function shouldGetFlags()
    {
        // given
        $standard = new StandardSpelling('', 'ui', new ThrowCondition());

        // when
        $flags = $standard->flags();

        // then
        $this->assertEquals(new Flags('ui'), $flags);
    }
}
