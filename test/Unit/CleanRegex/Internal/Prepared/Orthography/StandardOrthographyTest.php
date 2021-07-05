<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Orthography;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;

/**
 * @covers StandardOrthography
 */
class StandardOrthographyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiter()
    {
        // given
        $format = new StandardOrthography('#wel/come', '');

        // when
        $delimiter = $format->delimiter();

        // then
        $this->assertEquals(new Delimiter('%'), $delimiter);
    }

    /**
     * @test
     */
    public function shouldGetInputPattern()
    {
        // given
        $format = new StandardOrthography('#wel/{come}', '');

        // when
        $pattern = $format->pattern();

        // then
        $this->assertSame('#wel/{come}', $pattern);
    }

    /**
     * @test
     */
    public function shouldGetUndevelopedInput()
    {
        // given
        $format = new StandardOrthography('#wel/{come}', '');

        // when
        $undeveloped = $format->undevelopedInput();

        // then
        $this->assertSame('#wel/{come}', $undeveloped);
    }

    /**
     * @test
     */
    public function shouldGetFlags()
    {
        // given
        $format = new StandardOrthography('', 'ui');

        // when
        $flags = $format->flags();

        // then
        $this->assertEquals(new Flags('ui'), $flags);
    }
}
