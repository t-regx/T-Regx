<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Impl\ThrowFactory;
use Test\Utils\Impl\ThrowGroupAware;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\StringSubject;

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
        $stream = new MatchGroupStream(
            new ApiBase(Definitions::pattern('(?<group>\w+)'), new StringSubject('foo bar cat'), new UserData()),
            new ThrowGroupAware(),
            new GroupName('group'),
            new ThrowFactory());

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
        $stream = new MatchGroupStream(
            new ApiBase(Definitions::pattern('(?<group>\w+)'), new StringSubject('foo bar'), new UserData()),
            new ThrowGroupAware(),
            new GroupName('group'),
            new ThrowFactory());

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
        $stream = new MatchGroupStream(
            new ApiBase(Definitions::pattern('(?<group>\w+)'), new StringSubject('foo bar'), new UserData()),
            new ThrowGroupAware(),
            new GroupName('group'),
            new ThrowFactory());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_groupIndex_AllMatches()
    {
        // given
        $stream = new MatchGroupStream(
            new ApiBase(Definitions::pattern('(?<group>\w+)'), new StringSubject('foo bar cat'), new UserData()),
            new ThrowGroupAware(),
            new GroupName('group'),
            new ThrowFactory());

        // when
        $all = $stream->all();

        // then
        $this->assertSame(1, $all[0]->index());
        $this->assertSame(1, $all[1]->index());
        $this->assertSame(1, $all[2]->index());
    }

    /**
     * @test
     */
    public function shouldGet_matchAll_AllMatches()
    {
        // given
        $stream = new MatchGroupStream(
            new ApiBase(Definitions::pattern('(?<group>\w+)'), new StringSubject('foo bar cat'), new UserData()),
            new ThrowGroupAware(),
            new GroupName('group'),
            new ThrowFactory());

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['foo', 'bar', 'cat'], $all[0]->all());
        $this->assertSame(['foo', 'bar', 'cat'], $all[1]->all());
        $this->assertSame(['foo', 'bar', 'cat'], $all[2]->all());
    }

    /**
     * @test
     */
    public function shouldGet_matchAll_FirstMatch()
    {
        // given
        $stream = new MatchGroupStream(
            new ApiBase(Definitions::pattern('(?<group>\w+)'), new StringSubject('foo bar cat'), new UserData()),
            new ThrowGroupAware(),
            new GroupName('group'),
            new EagerMatchAllFactory(new RawMatchesOffset([
                1 => [
                    ['sword', 1],
                    ['bow', 2],
                    ['axe', 3],
                ],
            ])));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['sword', 'bow', 'axe'], $first->all());
    }
}
