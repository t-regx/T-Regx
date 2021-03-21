<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ThrowSubject;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchStream;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $stream = $this->matchStream($this->streamAll('14'));

        // when
        /** @var Detail[] $all */
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
        $stream = $this->matchStream($this->streamFirstKey(123));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(123, $firstKey);
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
        $stream = $this->matchStream($this->streamAll('19'));

        // when
        /** @var Detail[] $all */
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
        $stream = $this->matchStream($this->streamAll('14'));

        // when
        /** @var Detail[] $all */
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

    private function streamAll($firstValue): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method('all')->willReturn($this->matchesOffset($firstValue));
        $stream->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $stream;
    }

    private function streamFirstAndKey(string $value, int $index): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method('first')->willReturn($this->matchOffset($value));
        $stream->expects($this->once())->method('firstKey')->willReturn($index);
        $stream->expects($this->never())->method($this->logicalNot($this->logicalOr($this->matches('first'), $this->matches('firstKey'))));
        return $stream;
    }

    private function streamFirstKey($value): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method('firstKey')->willReturn($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches('firstKey')));
        return $stream;
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

    private function matchStream(BaseStream $stream, MatchAllFactory $factory = null): MatchStream
    {
        return new MatchStream($stream, new ThrowSubject(), new UserData(), $factory ?? $this->mock());
    }

    private function mock(): MatchAllFactory
    {
        /** @var MatchAllFactory|MockObject $factory */
        $factory = $this->createMock(MatchAllFactory::class);
        $factory->expects($this->never())->method($this->anything());
        return $factory;
    }
}
