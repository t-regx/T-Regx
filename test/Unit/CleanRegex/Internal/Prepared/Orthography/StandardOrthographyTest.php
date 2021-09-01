<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Orthography;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography
 */
class StandardOrthographyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiter()
    {
        // given
        $standard = new StandardOrthography('#wel/come', '');

        // when
        $delimiter = $standard->delimiter();

        // then
        $this->assertEquals(new Delimiter('%'), $delimiter);
    }

    /**
     * @test
     */
    public function shouldGetInputPattern()
    {
        // given
        $standard = new StandardOrthography('#wel/{come}', '');

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
        $standard = new StandardOrthography('#wel/{come}', '');

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
        $standard = new StandardOrthography('', 'ui');

        // when
        $flags = $standard->flags();

        // then
        $this->assertEquals(new Flags('ui'), $flags);
    }
}
