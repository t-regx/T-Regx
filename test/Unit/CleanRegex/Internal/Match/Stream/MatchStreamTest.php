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
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

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
        $stream = $this->matchStream(new AllStreamBase($this->matchesOffset('14')));

        // when
        $all = $stream->all();

        // then
        $this->assertSame('14', $all[0]->text());
        $this->assertSame('19', $all[1]->text());
        $this->assertSame('25', $all[2]->text());
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $stream = $this->matchStream($this->streamFirstAndKey('192', 0));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('192', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = $this->matchStream($this->streamFirstAndKey('192', 2));

        // when
        $key = $stream->firstKey();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldCreateFirstMatch_index()
    {
        // given
        $stream = $this->matchStream($this->streamFirstAndKey('192', 4));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(4, $first->index());
    }

    /**
     * @test
     */
    public function shouldCreateAllMatches_index()
    {
        // given
        $stream = $this->matchStream(new AllStreamBase($this->matchesOffset('19')));

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
        $stream = $this->matchStream(new AllStreamBase($this->matchesOffset('14')));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['14', '19', '25'], $all[0]->all());
        $this->assertSame(['14', '19', '25'], $all[1]->all());
        $this->assertSame(['14', '19', '25'], $all[2]->all());
    }

    /**
     * @test
     */
    public function shouldGetAll_first()
    {
        // given
        $stream = $this->matchStream($this->streamFirstAndKey('', 0), new EagerMatchAllFactory($this->matchesOffset('First')));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['First', '19', '25'], $first->all());
    }

    private function streamFirstAndKey(string $value, int $index): StreamBase
    {
        return new FirstStreamBase($index, $this->matchOffset($value));
    }

    private function matchesOffset(string $firstValue): RawMatchesOffset
    {
        return new RawMatchesOffset([[
            [$firstValue, 1],
            ['19', 2],
            ['25', 3],
        ]]);
    }

    private function matchOffset(string $value): RawMatchOffset
    {
        return new RawMatchOffset([[$value, 1]], 0);
    }

    private function matchStream(StreamBase $stream, MatchAllFactory $factory = null): MatchStream
    {
        return new MatchStream($stream, new ThrowSubject(), new UserData(), $factory ?? new ThrowFactory());
    }
}
