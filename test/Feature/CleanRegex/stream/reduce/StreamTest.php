<?php
namespace Test\Feature\CleanRegex\stream\reduce;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\StreamTerminal
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReduce_returnAccumulatorForEmptyMatch()
    {
        // when
        $result = ArrayStream::empty()->reduce(Functions::fail(), 12);
        // then
        $this->assertSame(12, $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passFromCallbackForSingleMatch()
    {
        // when
        $result = ArrayStream::of(['Input'])->reduce(Functions::constant('Lorem'), 'Accumulator');
        // then
        $this->assertSame('Lorem', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passAccumulatorAsFirstArgument()
    {
        // when
        $result = ArrayStream::of(['Input'])->reduce(Functions::identity(), 'Accumulator');
        // then
        $this->assertSame('Accumulator', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentString()
    {
        // given
        $secondString = function ($acc, string $string) {
            return $string;
        };
        // when
        $result = ArrayStream::of(['Input'])->reduce($secondString, 'Accumulator');
        // then
        $this->assertSame('Input', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_throwForTooManyArguments()
    {
        // given
        $tooManyArguments = function ($one, $two, $three) {
            $this->fail();
        };

        // then
        $this->expectException(\ArgumentCountError::class);
        // when
        ArrayStream::of(['Input'])->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForInvalidArgument()
    {
        // given
        $tooManyArguments = function ($one, int $invalid) {
            $this->fail();
        };

        // then
        $this->expectException(\TypeError::class);
        // when
        ArrayStream::of(['Input'])->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForNonCallback()
    {
        // then
        $this->expectException(\TypeError::class);
        // when
        ArrayStream::of(['Input'])->reduce(null, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduceSecond()
    {
        // when
        $reduced = ArrayStream::of(['Foo', 'Bar'])->reduce(Functions::secondArgument(), 'Accumulator');
        // then
        $this->assertSame('Bar', $reduced);
    }

    /**
     * @test
     */
    public function shouldPassAccumulator()
    {
        // when
        $reduced = ArrayStream::of(['15', 16, 17])->reduce(Functions::sum(), 0);
        // then
        $this->assertSame(48, $reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // when
        $reduced = ArrayStream::of(['Foo', 'Foo'])->reduce(Functions::constant(null), 0);
        // then
        $this->assertNull($reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNullAccumulator()
    {
        // when
        $reduced = ArrayStream::unmatched()->reduce(Functions::fail(), null);
        // then
        $this->assertNull($reduced);
    }
}
