<?php
namespace Test\Feature\CleanRegex\Match\stream\Detail;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();
        // when
        [$first, $second, $third] = $stream->all();
        // then
        $this->assertDetailText('123', $first);
        $this->assertDetailText('456', $second);
        $this->assertDetailText('789', $third);
    }

    /**
     * @test
     */
    public function shouldGetFirstTextAndIndex()
    {
        // given
        $stream = pattern('\d+')->match('123, 456, 789')->stream();
        // when
        $first = $stream->first();
        // then
        $this->assertDetailText('123', $first);
        $this->assertDetailIndex(0, $first);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames_lastGroup()
    {
        // when
        $stream = pattern('Foo(?<one>Bar)?(?<two>Bar)?')
            ->match('Foo')
            ->stream();
        // when
        $detail = $stream->first();
        // then
        $this->assertEquals(['one', 'two'], $detail->groupNames());
        $this->assertTrue($detail->groupExists('one'));
    }

    /**
     * @test
     */
    public function shouldFindFirstDetail()
    {
        // when
        $detail = pattern('Foo')->match('ę Foo')->stream()->findFirst()->get();
        // then
        $this->assertSame(2, $detail->offset());
        $this->assertSame(3, $detail->length());
    }
}
