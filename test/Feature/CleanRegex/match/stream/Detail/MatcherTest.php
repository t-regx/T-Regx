<?php
namespace Test\Feature\CleanRegex\match\stream\Detail;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = Pattern::of('\d+')->match('123,456,789')->stream();
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
        $stream = Pattern::of('\d+')->match('123, 456, 789')->stream();
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
        $stream = Pattern::of('Foo(?<one>Bar)?(?<two>Bar)?')
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
        $detail = Pattern::of('Foo')->match('ę Foo')->stream()->findFirst()->get();
        // then
        $this->assertSame(2, $detail->offset());
        $this->assertSame(3, $detail->length());
    }
}
