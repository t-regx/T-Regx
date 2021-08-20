<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\AllStreamBase;
use Test\Utils\Impl\FirstStreamBase;
use Test\Utils\Impl\ThrowFactory;
use Test\Utils\Impl\ThrowSubject;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\MatchStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MatchStream
 */
class MatchStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $stream = $this->matchStream(AllStreamBase::texts(['foo', 'bar', '18']));

        // when
        $all = $stream->all();

        // then
        $this->assertSame('foo', $all[0]->text());
        $this->assertSame('bar', $all[1]->text());
        $this->assertSame('18', $all[2]->text());
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $stream = $this->matchStream(FirstStreamBase::entry(14, '192'));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('192', $first->text());
        $this->assertSame(14, $first->index());
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = $this->matchStream(FirstStreamBase::dummy());

        // when
        $key = $stream->firstKey();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldCreateAllMatches_index()
    {
        // given
        $stream = $this->matchStream(AllStreamBase::offsets([3, 4, 18]));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(0, $all[0]->index());
        $this->assertSame(1, $all[1]->index());
        $this->assertSame(2, $all[2]->index());
    }

    /**
     * @test
     */
    public function shouldGetAll_all()
    {
        // given
        $stream = $this->matchStream(AllStreamBase::texts(['foo', '19', '25']));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['foo', '19', '25'], $all[0]->all());
        $this->assertSame(['foo', '19', '25'], $all[1]->all());
        $this->assertSame(['foo', '19', '25'], $all[2]->all());
    }

    /**
     * @test
     */
    public function shouldGetAll_first()
    {
        // given
        $stream = $this->matchStream(FirstStreamBase::dummy(), new EagerMatchAllFactory(new RawMatchesOffset([[
            ['First', 1],
            ['19', 2],
            ['25', 3],
        ]])));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['First', '19', '25'], $first->all());
    }

    private function matchStream(StreamBase $stream, MatchAllFactory $factory = null): MatchStream
    {
        return new MatchStream($stream, new ThrowSubject(), new UserData(), $factory ?? new ThrowFactory());
    }
}
