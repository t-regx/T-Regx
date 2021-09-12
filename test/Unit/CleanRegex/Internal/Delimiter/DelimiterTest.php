<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\Delimiter
 */
class DelimiterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelimit()
    {
        // given
        $delimiter = new Delimiter('/');

        // when
        $delimited = $delimiter->delimited(new TextWord('foo/bar'), new Flags(''));

        // then
        $this->assertSame('/foo\/bar/', $delimited);
    }

    /**
     * @test
     */
    public function shouldDelimitWithFlags()
    {
        // given
        $delimiter = new Delimiter('#');

        // when
        $delimited = $delimiter->delimited(new TextWord('foo/bar#cat'), new Flags('i'));

        // then
        $this->assertSame('#foo/bar\#cat#i', $delimited);
    }
}
