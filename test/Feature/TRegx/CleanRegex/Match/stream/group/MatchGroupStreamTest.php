<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\group;

use PHPUnit\Framework\TestCase;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream
 */
class MatchGroupStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllGroups()
    {
        // given
        $stream = pattern('(?<group>\w+)')->match('foo bar cat')->group('group')->stream();

        // when
        $groups = $stream->all();

        // then
        $this->assertSame('foo', $groups[0]->text());
        $this->assertSame('bar', $groups[1]->text());
        $this->assertSame('cat', $groups[2]->text());
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = pattern('(?<group>\w+)')->match('foo bar cat')->group('group')->stream();

        // when
        $group = $stream->first();

        // then
        $this->assertSame('foo', $group->text());
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = pattern('(?<group>\w+)')->match('foo bar cat')->group('group')->stream();

        // when
        $firstKey = $stream->keys()->first();

        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_groupIndex_AllMatches()
    {
        // given
        $stream = pattern('(?<group>\w+)')->match('foo bar cat')->group('group')->stream();

        // when
        [$first, $second, $third] = $stream->all();

        // then
        $this->assertSame(1, $first->index());
        $this->assertSame(1, $second->index());
        $this->assertSame(1, $third->index());
    }

    /**
     * @test
     */
    public function shouldGet_matchAll_FirstMatch()
    {
        // given
        $stream = pattern('(?<group>\w+)!')->match('sword! bow! axe!')->group('group')->stream();

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['sword', 'bow', 'axe'], $first->all());
    }

    /**
     * @test
     */
    public function shouldGet_matchAll_AllMatches()
    {
        // given
        $stream = pattern('(?<group>\w+)')->match('foo bar cat')->group('group')->stream();

        // when
        [$first, $second, $third] = $stream->all();

        // then
        $this->assertSame(['foo', 'bar', 'cat'], $first->all());
        $this->assertSame(['foo', 'bar', 'cat'], $second->all());
        $this->assertSame(['foo', 'bar', 'cat'], $third->all());
    }
}
